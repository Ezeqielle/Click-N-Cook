<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0){

    require('../extensions/header.php');
    if(isset($_POST['add'])){

        if(isset($_POST['name']) AND isset($_POST['price']) AND !empty($_POST['name']) AND !empty($_POST['price'])){

            $reqExist = $db->prepare('SELECT * FROM MENU WHERE name = :name AND idFranchisee = :idFranchisee');
            $reqExist->execute(array(
                'name' => $_POST['name'],
                'idFranchisee' => $_SESSION['id']
            ));
            $nameExist = $reqExist->rowCount();

            if($nameExist == 0){

                if(!(strpos($_POST['name'], ' ') === 0)){

                    if($_POST['price'] > 0){
                        $_POST['typeOfFood'] = $_POST['typeOfFood'] == 1 ? 1 : 0;
                        $_POST['product_status'] = $_POST['product_status'] == 1 ? 1 : 0;
                        $reqProduct = $db->prepare('INSERT INTO MENU(name, price, idFranchisee) VALUES(:name , :price , :idFranchisee)');
                        $reqProduct->execute(array(
                            'name' => htmlspecialchars($_POST['name']),
                            'price' => htmlspecialchars($_POST['price']),
                            'idFranchisee' => $_SESSION['id']
                        ));

                        $reqMenu = $db->prepare('SELECT * FROM MENU WHERE idFranchisee = :id AND name = :name');
                        $reqMenu->execute(array(
                            'id' => $_SESSION['id'],
                            'name' => htmlspecialchars($_POST['name'])
                        ));
                        $menuData = $reqMenu->fetch();

                        $reqDish = $db->query('SELECT * FROM DISH WHERE idFranchisee = ' . $_SESSION['id']);
                        $dishVerify = $reqDish->fetchALL();

                        if($dishVerify != NULL) {
                            foreach($dishVerify as $dishData) {
                                if(isset($_POST['dish' . $dishData['id']]) AND !empty($_POST['dish' . $dishData['id']])) {
                                    foreach($_POST['dish' . $dishData['id']] as $dish){
                                        if(htmlspecialchars($dish) > 0) {
                                            $reqIngredientContains = $db->prepare('INSERT INTO CONTAINSDISHMENU(idDish, idMenu, quantity) VALUES(:idDish , :idMenu, :quantity)');
                                            $reqIngredientContains->execute(array(
                                                'idDish' => $dishData['id'],
                                                'idMenu' => $menuData['id'],
                                                'quantity' => htmlspecialchars($dish)
                                            ));
                                        }
                                    }
                                }
                            }
                            $reqDish->closeCursor();
                        }

                        header('Location: shopGestion.php');
                        exit;
                    } else {
                        $error = 'Price must be over 0$ !';
                    }
                } else {
                    $error = 'The name of your product musn\'t begin with a space !';
                }
            } else {
                $error = 'This product is already in the shop !';
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
                    echo '<article class="col-lg-12 radius">';
                    echo '<form method="post">';
                    echo '<label>Name :</label><input name="name" class="form-control">';
                    echo '<label>Price :</label><input name="price" class="form-control">';


                    $reqDish = $db->query('SELECT * FROM DISH WHERE idFranchisee = ' . $_SESSION['id']);
                    $dishVerify = $reqDish->fetchALL();

                    if($dishVerify != NULL) {
                        echo '<label>Ingredient :</label>';
                        echo '</br>';
                        foreach($dishVerify as $dishData) {
                            if($dishData['quantity'] > 0) {
                                echo '<label>' . $dishData['name'] . ' : </label> ';
                                echo '<select name="dish' . $dishData['id'] . '[]">';
                                for ($i = 0; $i <= $dishData['quantity']; $i++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                echo '</select>';
                                echo '</br>';
                            } else {
                                echo '<label>' . $dishData['name'] . ' : </label> ';
                                echo '<strong> The stock is empty ! </strong> ';
                                echo '</br>';
                            }
                        }
                        $reqDish->closeCursor();
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong>There is no product</strong>
                        </div>
                        <?php
                    }


                    echo '<button type="submit" name="add" class="btn btn-default btn-sm">Create</button>';
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