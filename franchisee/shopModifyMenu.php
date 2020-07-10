<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0 AND isset($_GET['id'])){

    require('../extensions/headerFranchisee.php');
    if(isset($_POST['modify'])){

        if(isset($_POST['name']) AND isset($_POST['price']) AND !empty($_POST['name']) AND !empty($_POST['price'])){

            if(!(strpos($_POST['name'], ' ') === 0)){

                if($_POST['price'] > 0){

                    $reqProduct = $db->prepare('UPDATE MENU SET name = :name , price = :price WHERE id = :id');
                    $reqProduct->execute(array(
                        'name' => htmlspecialchars($_POST['name']),
                        'price' => htmlspecialchars($_POST['price']),
                        'id' => $_GET['id']
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
                            if(isset($_POST['dishBis' . $dishData['id']]) AND !empty($_POST['dishBis' . $dishData['id']])) {
                                foreach($_POST['dishBis' . $dishData['id']] as $dishbis){
                                    if(htmlspecialchars($dishbis) > 0) {
                                        $reqDish = $db->prepare('INSERT INTO CONTAINSDISHMENU(idDish, idMenu, quantity) VALUES(:idDish , :idMenu , :quantity)');
                                        $reqDish->execute(array(
                                            'idDish' => $dishData['id'],
                                            'idMenu' => $menuData['id'],
                                            'quantity' => htmlspecialchars($dishbis)
                                        ));
                                    }
                                }
                            }
                            if(isset($_POST['dish' . $dishData['id']]) AND !empty($_POST['dish' . $dishData['id']])) {
                                foreach($_POST['dish' . $dishData['id']] as $dish){
                                    if(htmlspecialchars($dish) > 0) {
                                        $reqDishBis = $db->prepare('UPDATE CONTAINSDISHMENU SET quantity = :quantity WHERE idMenu = :idMenu AND idDish = :idDish');
                                        $reqDishBis->execute(array(
                                            'quantity' => htmlspecialchars($dish),
                                            'idMenu' => $menuData['id'],
                                            'idDish' => $dishData['id']
                                        ));
                                    } else {
                                        $reqDish = $db->prepare('DELETE FROM CONTAINSDISHMENU WHERE idMenu = :idMenu AND idDish = :idDish');
                                        $reqDish->execute(array(
                                            'idMenu' => $menuData['id'],
                                            'idDish' => $dishData['id']
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
                    $error = TXT_ADMINS_ERROR1;
                }
            } else {
                $error = TXT_EXTENSIONS_ERROR1;
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
                    $reqMenu = $db->prepare('SELECT * FROM MENU WHERE id = :id');
                    $reqMenu->execute(array(
                        'id' => $_GET['id']
                    ));
                    $menuData = $reqMenu->fetch();

                    echo '<article class="col-lg-12 radius">';
                    echo '<form method="post">';
                    echo '<label>' . TXT_ADMINS_NAME . '</label><input name="name" value="' . $menuData['name'] . '" class="form-control">';
                    echo '<label>' . TXT_ADMINS_PRICE . '</label><input name="price" value="' . $menuData['price'] . '" class="form-control">';

                    $reqDishContains = $db->query('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = ' . $menuData["id"]);
                    if(($dishContainsVerify = $reqDishContains->fetchALL()) != NULL) {
                        echo '<label>' . TXT_CLIENT_DISHNAME . '</label>';
                        echo '</br>';
                        $product[] = NULL;
                        $j = 0;
                        foreach ($dishContainsVerify as $dishContainsData) {
                            $reqDish = $db->query('SELECT * FROM DISH WHERE id = ' . $dishContainsData["idDish"]);
                            if (($dishVerify = $reqDish->fetchALL()) != NULL) {
                                foreach ($dishVerify as $dishData) {
                                    echo '<label>' . $dishData['name'] . ' : </label> ';
                                    echo '<input type="hidden" name="product' . $dishData['id'] . '" value="' . $dishData['id'] . '">';
                                    $product[$j] = $dishData['id'];
                                    echo '<select name="dish' . $dishData['id'] . '[]">';
                                    echo '<option value="' . $dishContainsData['quantity'] . '">' . $dishContainsData['quantity'] . '</option>';
                                    for($i = 0; $i <= $dishData['quantity']; $i++) {
                                        if($i != $dishContainsData['quantity']) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                    }
                                    echo '</select>';
                                    echo '</br>';
                                }
                                $j += 1;
                            }
                        }
                        $reqDishBis = $db->query('SELECT * FROM DISH WHERE idFranchisee = ' . $_SESSION['id']);
                        $dishVerifyBis = $reqDishBis->fetchALL();

                        $j = 0;
                        if($dishVerifyBis != NULL) {
                            foreach($dishVerifyBis as $dishDataBis) {
                                if($dishDataBis['id'] != $product[$j]) {
                                    if($dishDataBis['quantity'] > 0) {
                                        echo '<label>' . $dishDataBis['name'] . ' : </label> ';
                                        echo '<select name="dishBis' . $dishDataBis['id'] . '[]">';
                                        for ($i = 0; $i <= $dishDataBis['quantity']; $i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        echo '</select>';
                                        echo '</br>';
                                    } else {
                                        echo '<label>' . $dishDataBis['name'] . ' : </label> ';
                                        echo '<strong>' . TXT_CLIENT_STOCK . '</strong> ';
                                        echo '</br>';
                                    }
                                } else {
                                    $j++;
                                }
                            }
                            $reqDish->closeCursor();
                        }
                    }



                    echo '<button type="submit" name="modify" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_MOD . '</button>';
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