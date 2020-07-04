<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0 AND isset($_GET['id'])){

        require('../extensions/header.php');
        if(isset($_POST['modify'])){

            if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['quantity']) AND !empty($_POST['name']) AND !empty($_POST['price']) AND !empty($_POST['quantity'])){

                if(!(strpos($_POST['name'], ' ') === 0)){

                    if($_POST['price'] > 0){

                        if($_POST['quantity'] > 0){

                            $_POST['typeOfFood'] = $_POST['typeOfFood'] == 1 ? 1 : 0;
                            $_POST['product_status'] = $_POST['product_status'] == 1 ? 1 : 0;
                            $reqProduct = $db->prepare('UPDATE DISH SET name = :name , price = :price , quantity = :quantity WHERE id = :id');
                            $reqProduct->execute(array(
                                'name' => htmlspecialchars($_POST['name']),
                                'price' => htmlspecialchars($_POST['price']),
                                'quantity' => htmlspecialchars($_POST['quantity']),
                                'id' => $_GET['id']
                            ));

                            $reqDish = $db->prepare('SELECT * FROM DISH WHERE idFranchisee = :id AND name = :name');
                            $reqDish->execute(array(
                                'id' => $_SESSION['id'],
                                'name' => htmlspecialchars($_POST['name'])
                            ));
                            $dishData = $reqDish->fetch();

                            $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE idFranchisee = ' . $_SESSION['id']);
                            $ingredientVerify = $reqIngredient->fetchALL();

                            if($ingredientVerify != NULL) {
                                foreach($ingredientVerify as $ingredientData) {
                                    if(isset($_POST['ingredientBis' . $ingredientData['id']]) AND !empty($_POST['ingredientBis' . $ingredientData['id']])) {
                                        foreach($_POST['ingredientBis' . $ingredientData['id']] as $ingredientBis){
                                            if(htmlspecialchars($ingredientBis) > 0) {
                                                $reqIngredient = $db->prepare('INSERT INTO CONTAINSINGREDIENTSDISH(idIngredient, idDish, quantity) VALUES(:idIngredient , :idDish , :quantity)');
                                                $reqIngredient->execute(array(
                                                    'idIngredient' => $ingredientData['id'],
                                                    'idDish' => $dishData['id'],
                                                    'quantity' => htmlspecialchars($ingredientBis)
                                                ));

                                                $reqIngredientUpdate = $db->prepare('UPDATE INGREDIENT SET quantity = :quantity WHERE id = :id');
                                                $reqIngredientUpdate->execute(array(
                                                    'quantity' => $ingredientData['quantity'] - (htmlspecialchars($ingredientBis) * htmlspecialchars($_POST['quantity'])),
                                                    'id' => $ingredientData['id']
                                                ));
                                            }
                                        }
                                    }
                                    if(isset($_POST['ingredient' . $ingredientData['id']]) AND !empty($_POST['ingredient' . $ingredientData['id']])) {
                                        foreach($_POST['ingredient' . $ingredientData['id']] as $ingredient){
                                            if(htmlspecialchars($ingredient) > 0) {
                                                $reqIngredientBis = $db->prepare('UPDATE CONTAINSINGREDIENTSDISH SET quantity = :quantity WHERE idIngredient = :idIngredient AND idDish = :idDish');
                                                $reqIngredientBis->execute(array(
                                                    'quantity' => htmlspecialchars($ingredient),
                                                    'idIngredient' => $ingredientData['id'],
                                                    'idDish' => $dishData['id']
                                                ));
                                            } else {

                                                $reqContainsIngredientsDish = $db->prepare('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idIngredient = :idIngredient AND idDish = :idDish');
                                                $reqContainsIngredientsDish->execute(array(
                                                    'idIngredient' => $ingredientData['id'],
                                                    'idDish' => $dishData['id']
                                                ));
                                                $containsIngredientsDishData = $reqContainsIngredientsDish->fetch();

                                                $reqIngredientUpdate = $db->prepare('UPDATE INGREDIENT SET quantity = :quantity WHERE id = :id');
                                                $reqIngredientUpdate->execute(array(
                                                    'quantity' => $ingredientData['quantity'] + ($containsIngredientsDishData['quantity'] * htmlspecialchars($_POST['quantity'])),
                                                    'id' => $ingredientData['id']
                                                ));

                                                $reqIngredient = $db->prepare('DELETE FROM CONTAINSINGREDIENTSDISH WHERE idIngredient = :idIngredient AND idDish = :idDish');
                                                $reqIngredient->execute(array(
                                                    'idIngredient' => $ingredientData['id'],
                                                    'idDish' => $dishData['id']
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
                            $error = 'Stock must be over 0 !';
                        }
                    } else {
                        $error = 'Price must be over 0$ !';
                    }
                } else {
                    $error = 'The name of your product musn\'t begin with a space !';
                }
            } else {
                $error = 'Please fill in all the fields !';
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
                    $reqDish = $db->prepare('SELECT * FROM DISH WHERE id = :id');
                    $reqDish->execute(array(
                        'id' => $_GET['id']
                    ));
                    $dishData = $reqDish->fetch();

                    echo '<article class="col-lg-12 radius">';
                    echo '<form method="post">';
                    echo '<label>Name :</label><input name="name" value="' . $dishData['name'] . '" class="form-control">';
                    echo '<label>Price :</label><input name="price" value="' . $dishData['price'] . '" class="form-control">';
                    echo '<label>Quantity :</label><input name="quantity" value="' . $dishData['quantity'] . '" class="form-control">';

                    $reqIngredientContains = $db->query('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = ' . $dishData["id"]);
                    if(($ingredientContainsVerify = $reqIngredientContains->fetchALL()) != NULL) {
                        echo '<label>Ingredient :</label>';
                        echo '</br>';
                        $product[] = NULL;
                        $j = 0;
                        foreach ($ingredientContainsVerify as $ingredientContainsData) {
                            $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE id = ' . $ingredientContainsData["idIngredient"]);
                            if (($ingredientVerify = $reqIngredient->fetchALL()) != NULL) {
                                foreach ($ingredientVerify as $ingredientData) {
                                    echo '<label>' . $ingredientData['name'] . ' : </label> ';
                                    echo '<input type="hidden" name="product' . $ingredientData['id'] . '" value="' . $ingredientData['id'] . '">';
                                    $product[$j] = $ingredientData['id'];
                                    echo '<select name="ingredient' . $ingredientData['id'] . '[]">';
                                    echo '<option value="' . $ingredientContainsData['quantity'] . '">' . $ingredientContainsData['quantity'] . '</option>';
                                    for($i = 0; $i <= $ingredientData['quantity']; $i++) {
                                        if($i != $ingredientContainsData['quantity']) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    echo '</select>';
                                    echo '</br>';
                                }
                                $j += 1;
                            }
                        }
                        $reqIngredientBis = $db->query('SELECT * FROM INGREDIENT WHERE idFranchisee = ' . $_SESSION['id']);
                        $ingredientVerifyBis = $reqIngredientBis->fetchALL();

                        $j = 0;
                        if($ingredientVerifyBis != NULL) {
                            foreach($ingredientVerifyBis as $ingredientDataBis) {
                                if($ingredientDataBis['id'] != $product[$j]) {
                                    if($ingredientDataBis['quantity'] > 0) {
                                        echo '<label>' . $ingredientDataBis['name'] . ' : </label> ';
                                        echo '<select name="ingredientBis' . $ingredientDataBis['id'] . '[]">';
                                        for ($i = 0; $i <= $ingredientDataBis['quantity']; $i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        echo '</select>';
                                        echo '</br>';
                                    } else {
                                        echo '<label>' . $ingredientDataBis['name'] . ' : </label> ';
                                        echo '<strong> The stock is empty ! </strong> ';
                                        echo '</br>';
                                    }
                                } else {
                                    $j++;
                                }
                            }
                            $reqIngredient->closeCursor();
                        }
                    }



                    echo '<button type="submit" name="modify" class="btn btn-default btn-sm">Modify</button>';
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