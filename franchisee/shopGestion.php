<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0){

    require('../extensions/headerFranchisee.php');

    $_SESSION['idFranchiseeSales'] = $_SESSION['id'];

    if(isset($_POST['delete'])){
        $reqContainsSale = $db->prepare('SELECT * FROM CONTAINSMENUSALE WHERE idMenu = :id');
        $reqContainsSale->execute(array(
            'id' => $_POST['productId']
        ));

        if($reqContainsSale->rowCount() > 0) {
            $error = TXT_FRANCHISEE_ERROR3;
        } else {
            $delContainsDishMenu = $db->prepare('DELETE FROM CONTAINSDISHMENU WHERE idMenu = :id');
            $delContainsDishMenu->execute(array(
                'id' => $_POST['productId']
            ));

            $delMenu = $db->prepare('DELETE FROM MENU WHERE id = :id');
            $delMenu->execute(array(
                'id' => $_POST['productId']
            ));
        }
    }

    if(isset($_POST['deleteDish'])){
        $reqContainsSale = $db->prepare('SELECT * FROM CONTAINSDISHSALE WHERE idDish = :id');
        $reqContainsSale->execute(array(
            'id' => $_POST['productId']
        ));


        $reqContainsSaleMenu = $db->prepare('SELECT * FROM CONTAINSDISHMENU WHERE idDish = :id');
        $reqContainsSaleMenu->execute(array(
            'id' => $_POST['productId']
        ));

        if($reqContainsSale->rowCount() > 0) {
            $error = TXT_FRANCHISEE_ERROR3;
        } else if($reqContainsSaleMenu->rowCount > 0) {
            $error = TXT_FRANCHISEE_ERROR4;
        } else {
            $delDish = $db->prepare('DELETE FROM DISH WHERE id = :id');
            $delDish->execute(array(
                'id' => $_POST['productId']
            ));
        }
    }

    if(isset($_POST['deleteIngredient'])){
        $reqContainsSale = $db->prepare('SELECT * FROM CONTAINSDISHSALE WHERE idDish = :id');
        $reqContainsSale->execute(array(
            'id' => $_POST['productId']
        ));

        if($reqContainsSale->rowCount() > 0) {
            $error = TXT_FRANCHISEE_ERROR3;
        } else {
            $reqDishIngredient = $db->prepare('SELECT * FROM DISH WHERE id = :id');
            $reqDishIngredient->execute(array(
                'id' => $_POST['productId']
            ));
            while($dishData = $reqDishIngredient->fetch()){
                $reqIngredientContains = $db->query('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = ' . $dishData["id"]);
                if (($ingredientContainsVerify = $reqIngredientContains->fetchALL()) != NULL) {
                    foreach ($ingredientContainsVerify as $ingredientContainsData) {
                        $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE id = ' . $ingredientContainsData["idIngredient"]);
                        if (($ingredientVerify = $reqIngredient->fetchALL()) != NULL) {
                            foreach ($ingredientVerify as $ingredientData) {
                                $changeQuantity = $db->prepare('UPDATE INGREDIENT SET quantity = :quantity WHERE id = :id');
                                $changeQuantity->execute(array(
                                    'quantity' => ($ingredientContainsData['quantity'] * $dishData['quantity']) + $ingredientData['quantity'],
                                    'id' => $ingredientContainsData['idIngredient']
                                ));
                            }
                        }
                    }
                }
                $reqDishIngredient->closeCursor();
            }

            $delContainsIngredientsDish = $db->prepare('DELETE FROM CONTAINSINGREDIENTSDISH WHERE idDish = :id');
            $delContainsIngredientsDish->execute(array(
                'id' => $_POST['productId']
            ));

            $delDish = $db->prepare('DELETE FROM DISH WHERE id = :id');
            $delDish->execute(array(
                'id' => $_POST['productId']
            ));
        }
    }
    ?>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <article class="col-lg-12 radius">
                        <center>
                            <a role="button" href="franchiseeSalesHistory.php" class="btn btn-default btn-sm">
                                <?php echo TXT_EXTENSIONS_SALE; ?>
                            </a>
                            </br>
                            <?= isset($error) ? '<font color="red">' . $error . "</font><br>" : ''; ?>
                        </center>
                    </article>
                </div>
                <div class="row">
                    <article class="col-lg-6 radius">
                        <center>
                            <strong>
                                <p><?php echo TXT_FRANCHISEE_YMENU; ?></p>
                            </strong>
                            <a role="button" href="shopAddMenu.php" class="btn btn-default btn-sm">
                                <?php echo TXT_FRANCHISEE_AMENU; ?>
                            </a>
                        </center>
                    </article>
                    <article class="col-lg-6 radius">
                        <center>
                            <strong>
                                <p><?php echo TXT_FRANCHISEE_YDISH; ?></p>
                            </strong>
                            <a role="button" href="shopAddDish.php" class="btn btn-default btn-sm">
                                <?php echo TXT_FRANCHISEE_ADISH; ?>
                            </a>
                        </center>
                    </article>
                </div>
                <article class="col-lg-6 radius" style="border-left-width: 0;">
                    <?php

                    $reqMenu = $db->query('SELECT * FROM MENU WHERE idFranchisee = ' . $_SESSION["id"]);

                    if(($menuVerify = $reqMenu->fetchALL()) != NULL) {
                        echo '<div class="col" id="result">';
                        foreach($menuVerify as $menuData) {
                            $reqDishContains = $db->query('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = ' . $menuData["id"]);
                            if(($dishContainsVerify = $reqDishContains->fetchALL()) != NULL) {
                                echo '<form method="post" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                echo '<label>' . TXT_FRANCHISEE_MENU . '</label> <br>';
                                echo $menuData['name'] . '<br>';
                                echo $menuData['price'] . TXT_CLIENT_MONNEY . '<br>';
                                echo '<input type="hidden" name="productId" value="' . htmlspecialchars($menuData['id']) . '">';
                                echo '<label>' . TXT_CLIENT_DISHNAME . '</label> <br>';
                                foreach($dishContainsVerify as $dishContainsData) {
                                    $reqDish = $db->query('SELECT * FROM DISH WHERE id = ' . $dishContainsData["idDish"]);
                                    if(($dishVerify = $reqDish->fetchALL()) != NULL) {
                                        foreach($dishVerify as $dishData) {
                                            echo $dishData['name'] . ' : ';
                                            echo $dishContainsData['quantity'] . '<br>';
                                        }
                                    }
                                }
                                echo '<button type="submit" name="delete" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button>';
                                echo '   <a class="btn btn-default btn-sm" href="shopModifyMenu.php?id=' . $menuData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
                                echo '</form>';
                            }
                        }
                        $reqMenu->closeCursor();
                        echo '</div>';
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong><?php echo TXT_FRANCHISEE_NPRODUCT; ?></strong>
                        </div>
                        <?php
                    }
                    ?>
                </article>
                <article class="col-lg-6 radius" style="border-right-width: 0;">
                    <?php

                    $reqDish = $db->query('SELECT * FROM DISH WHERE idFranchisee = ' . $_SESSION["id"]);

                    if(($dishVerify = $reqDish->fetchALL()) != NULL) {
                        echo '<div class="col" id="result">';
                        foreach($dishVerify as $dishData) {
                            $reqIngredientContains = $db->query('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = ' . $dishData["id"]);
                            if(($ingredientContainsVerify = $reqIngredientContains->fetchALL()) != NULL) {
                                echo '<form method="post" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                echo '<label>' . TXT_CLIENT_DISHNAME . '</label> <br>';
                                echo $dishData['name'] . '<br>';
                                echo $dishData['quantity'] . TXT_EXTENSIONS_LEFT . '<br>';
                                echo $dishData['price'] . TXT_CLIENT_MONNEY . '<br>';
                                echo '<input type="hidden" name="productId" value="' . htmlspecialchars($dishData['id']) . '">';
                                echo '<label>' . TXT_EXTENSIONS_ING . '</label> <br>';
                                foreach($ingredientContainsVerify as $ingredientContainsData) {
                                    $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE id = ' . $ingredientContainsData["idIngredient"]);
                                    if(($ingredientVerify = $reqIngredient->fetchALL()) != NULL) {
                                        foreach($ingredientVerify as $ingredientData) {
                                            echo $ingredientData['name'] . ' : ';
                                            echo $ingredientContainsData['quantity'] . '<br>';
                                        }
                                    }
                                }
                                echo '<button type="submit" name="deleteIngredient" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button>';
                                echo '   <a class="btn btn-default btn-sm" href="shopModifyDish.php?id=' . $dishData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
                                echo '</form>';
                            } else {
                                echo '<form method="post" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                echo '<label>' . TXT_CLIENT_DISHNAME . '</label> <br>';
                                echo $dishData['name'] . '<br>';
                                echo $dishData['quantity'] . TXT_EXTENSIONS_LEFT . '<br>';
                                echo $dishData['price'] . TXT_CLIENT_MONNEY . '<br>';
                                echo '<input type="hidden" name="productId" value="' . htmlspecialchars($dishData['id']) . '">';
                                echo '<button type="submit" name="deleteDish" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button>';
                                echo '   <a class="btn btn-default btn-sm" href="shopModifyDish.php?id=' . $dishData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
                                echo '</form>';
                            }
                        }
                        $reqDish->closeCursor();
                        echo '</div>';
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong><?php echo TXT_FRANCHISEE_NPRODUCT; ?></strong>
                        </div>
                        <?php
                    }
                    ?>
                </article>
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