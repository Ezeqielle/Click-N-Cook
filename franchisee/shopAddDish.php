<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0){

    require('../extensions/headerFranchisee.php');
    if(isset($_POST['add'])){

        if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['quantity']) AND !empty($_POST['name']) AND !empty($_POST['price']) AND !empty($_POST['quantity'])){

            $reqExist = $db->prepare('SELECT * FROM DISH WHERE name = :name AND idFranchisee = :idFranchisee');
            $reqExist->execute(array(
                'name' => $_POST['name'],
                'idFranchisee' => $_SESSION['id']
            ));
            $nameExist = $reqExist->rowCount();

            if($nameExist == 0){

                if(!(strpos($_POST['name'], ' ') === 0)){

                    if($_POST['price'] > 0){

                        if($_POST['quantity'] > 0){

                            $verifyQuantity = 1;

                            $reqIngredientVerify = $db->query('SELECT * FROM INGREDIENT WHERE idFranchisee = ' . $_SESSION['id']);
                            $ingredientBisVerify = $reqIngredientVerify->fetchALL();
                            if($ingredientBisVerify != NULL) {
                                foreach($ingredientBisVerify as $ingredientDataVerify) {
                                    if(isset($_POST['ingredient' . $ingredientDataVerify['id']]) AND !empty($_POST['ingredient' . $ingredientDataVerify['id']])) {
                                        foreach($_POST['ingredient' . $ingredientDataVerify['id']] as $ingredientVerify){
                                            if(htmlspecialchars($ingredientVerify) > 0) {
                                                if((htmlspecialchars($ingredientVerify) * htmlspecialchars($_POST['quantity'])) > $ingredientDataVerify['quantity']) {
                                                    $verifyQuantity = 0;
                                                }
                                            }
                                        }
                                    }
                                }
                                $reqIngredientVerify->closeCursor();
                            }

                            if($verifyQuantity > 0) {

                                $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE idFranchisee = ' . $_SESSION['id']);
                                $ingredientVerify = $reqIngredient->fetchALL();


                                $reqProduct = $db->prepare('INSERT INTO DISH(name, price, quantity, idFranchisee) VALUES(:name , :price , :quantity, :idFranchisee)');
                                $reqProduct->execute(array(
                                    'name' => htmlspecialchars($_POST['name']),
                                    'price' => htmlspecialchars($_POST['price']),
                                    'quantity' => htmlspecialchars($_POST['quantity']),
                                    'idFranchisee' => $_SESSION['id']
                                ));

                                $reqDish = $db->prepare('SELECT * FROM DISH WHERE idFranchisee = :id AND name = :name');
                                $reqDish->execute(array(
                                    'id' => $_SESSION['id'],
                                    'name' => htmlspecialchars($_POST['name'])
                                ));
                                $dishData = $reqDish->fetch();


                                if($ingredientVerify != NULL) {
                                    foreach($ingredientVerify as $ingredientData) {
                                        if(isset($_POST['ingredient' . $ingredientData['id']]) AND !empty($_POST['ingredient' . $ingredientData['id']])) {
                                            foreach($_POST['ingredient' . $ingredientData['id']] as $ingredient){
                                                if(htmlspecialchars($ingredient) > 0) {
                                                    $reqIngredientContains = $db->prepare('INSERT INTO CONTAINSINGREDIENTSDISH(idIngredient, idDish, quantity) VALUES(:idIngredient , :idDish , :quantity)');
                                                    $reqIngredientContains->execute(array(
                                                        'idIngredient' => $ingredientData['id'],
                                                        'idDish' => $dishData['id'],
                                                        'quantity' => htmlspecialchars($ingredient)
                                                    ));

                                                    $reqIngredientUpdate = $db->prepare('UPDATE INGREDIENT SET quantity = :quantity WHERE id = :id');
                                                    $reqIngredientUpdate->execute(array(
                                                        'quantity' => $ingredientData['quantity'] - (htmlspecialchars($ingredient) * htmlspecialchars($_POST['quantity'])),
                                                        'id' => $ingredientData['id']
                                                    ));

                                                }
                                            }
                                        }
                                    }
                                    $reqIngredient->closeCursor();
                                }

                                header('Location: shopGestion.php');
                                exit;
                            } else {
                                $error = TXT_FRANCHISEE_BIG;
                            }
                        } else {
                            $error = TXT_ADMINS_ERROR1;
                        }
                    } else {
                        $error = TXT_ADMINS_ERROR2;
                    }
                } else {
                    $error = TXT_EXTENSIONS_ERROR1;
                }
            } else {
                $error = TXT_EXTENSIONS_ERROR2;
            }
        } else {
            $error = TXT_FUNCTIONS_ERROR11;
        }
    }
    ?>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <?php
                    echo '<article class="col-lg-12 radius">';
                    echo '<form method="post">';
                    echo '<label>' . TXT_ADMINS_NAME . '</label><input name="name" class="form-control">';
                    echo '<label>' . TXT_ADMINS_PRICE . '</label><input name="price" class="form-control">';
                    echo '<label>' . TXT_ADMINS_QTY . '</label><input name="quantity" class="form-control">';


                    $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE idFranchisee = ' . $_SESSION['id']);
                    $ingredientVerify = $reqIngredient->fetchALL();

                    if($ingredientVerify != NULL) {
                        echo '<label>' . TXT_EXTENSIONS_ING . '</label>';
                        echo '</br>';
                        foreach($ingredientVerify as $ingredientData) {
                            if($ingredientData['quantity'] > 0) {
                                echo '<label>' . $ingredientData['name'] . ' : </label> ';
                                echo '<select name="ingredient' . $ingredientData['id'] . '[]">';
                                for ($i = 0; $i <= $ingredientData['quantity']; $i++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                echo '</select>';
                                echo '</br>';
                            } else {
                                echo '<label>' . $ingredientData['name'] . ' : </label> ';
                                echo '<strong>' . TXT_CLIENT_STOCK . ' </strong> ';
                                echo '</br>';
                            }
                        }
                        $reqIngredient->closeCursor();
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong><?php echo TXT_CLIENT_NPRODUCT; ?></strong>
                        </div>
                        <?php
                    }


                    echo '<button type="submit" name="add" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_CREATE . '</button>';
                    echo '</form>';
                    if(isset($error)){
                        echo '<font color="red">'. $error ."</font>";
                    }
                    echo '</article>';
                    ?>
                </div>
            </section>
            <section class="col-lg-3">
            </section>
        </div>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>