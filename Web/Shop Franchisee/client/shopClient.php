<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../extensions/lang.php');
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 2) {

    require('../extensions/header.php');
    ?>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <article class="col-lg-12 radius">
                        <center>
                            <button type="button" id="btn" class="btn btn-default btn-sm">BUY</button>
                            <?php
                            $reqExistOrder = $db->prepare('SELECT * FROM PURCHASECLIENT WHERE idClient = :currentId AND date IS NULL');
                            $reqExistOrder->execute(array(
                                'currentId' => $_SESSION['id']
                            ));
                            if($reqExistOrder->rowCount() > 0) {
                                echo '<a href="shopPaymentClient.php" class="btn btn-default btn-sm">Last Bill</a>';
                            }
                            if(isset($_GET['payment']) AND !empty($_GET['payment']) AND $_GET['payment'] == 'success') {
                                require_once "../sendMail/invoiceClientBuy.php";

                                invoiceClientMail();
                                echo '<div class="alert alert-success alert-dismissible">
                                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                      <strong>Success!</strong> Your payment was successfully.
                                  </div>';
                            }

                            if(isset($_GET['insert']) AND !empty($_GET['insert']) AND $_GET['insert'] == 'error') {
                                echo '<div class="alert alert-danger alert-dismissible">
                                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                      <strong>Error!</strong> You have selected 1 menu and 1 dish but the addition of the products is not enough with regard to the stock.
                                  </div>';
                            }
                            ?>
                        </center>
                    </article>
                </div>
                <div class="row">
                    <article class="col-lg-6 radius">
                        <center>
                            <strong>
                                <p>Choose a Menu :</p>
                            </strong>
                        </center>
                    </article>
                    <article class="col-lg-6 radius">
                        <center>
                            <strong>
                                <p>Choose a Dish :</p>
                            </strong>
                        </center>
                    </article>
                </div>
                <article class="col-lg-6 radius" style="border-left-width: 0;">
                    <?php

                    $reqMenu = $db->query('SELECT * FROM MENU WHERE idFranchisee = ' . $_SESSION["idFranchisee"]);

                    if(($menuVerify = $reqMenu->fetchALL()) != NULL) {
                        $verifyQuantityDish = 1;
                        $arrayMenuVerify[] = NULL;
                        $arrayQuantity[] = NULL;
                        $arrayQuantityVerify[] = NULL;
                        foreach($menuVerify as $menuData) {
                            $arrayMin = 100000000000000;
                            $reqDishContains = $db->query('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = ' . $menuData["id"]);
                            if(($dishContainsVerify = $reqDishContains->fetchALL()) != NULL) {
                                foreach($dishContainsVerify as $dishContainsData) {
                                    $reqDish = $db->query('SELECT * FROM DISH WHERE id = ' . $dishContainsData["idDish"]);
                                    if(($dishVerify = $reqDish->fetchALL()) != NULL) {
                                        foreach($dishVerify as $dishData) {
                                            if($dishContainsData['quantity'] > $dishData['quantity']) {
                                                $arrayMenuVerify[$menuData['id']] = $menuData['id'];
                                            } else {
                                                $arrayQuantity[$dishContainsData['idDish'] . $menuData['id']] = 0;
                                                $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']] = 0;
                                                while($arrayQuantity[$dishContainsData['idDish'] . $menuData['id']] < $dishData['quantity']) {
                                                        $arrayQuantity[$dishContainsData['idDish'] . $menuData['id']] = $arrayQuantity[$dishContainsData['idDish'] . $menuData['id']] + $dishContainsData['quantity'];
                                                        $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']]++;
                                                }
                                                if($arrayQuantity[$dishContainsData['idDish'] . $menuData['id']] > $dishData['quantity']) {
                                                    $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']]--;
                                                }
                                                if($arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']] < $arrayMin) {
                                                    $arrayMin = $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']];
                                                } else {
                                                    $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']] = $arrayMin;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $reqMenu->closeCursor();

                        $emptyMenu = 0;
                        $emptyDish = 0;
                        echo '<div class="col" id="results">';
                        foreach ($menuVerify as $menuData) {
                            if($arrayMenuVerify[$menuData['id']] != $menuData['id']) {
                                if ($dishData['quantity'] > 0) {
                                    $reqDishContains = $db->query('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = ' . $menuData["id"]);
                                    if (($dishContainsVerify = $reqDishContains->fetchALL()) != NULL) {
                                        echo '<form method="post" name="menu" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                        echo '<label>Menu :</label> <br>';
                                        echo $menuData['name'] . '<br>';
                                        echo $menuData['price'] . ' $<br>';
                                        echo '<input type="hidden" name="productId" value="' . htmlspecialchars($menuData['id']) . '">';
                                        echo '<label>Dish :</label> <br>';
                                        foreach ($dishContainsVerify as $dishContainsData) {
                                            $reqDish = $db->query('SELECT * FROM DISH WHERE id = ' . $dishContainsData["idDish"]);
                                            if (($dishVerify = $reqDish->fetchALL()) != NULL) {
                                                foreach ($dishVerify as $dishData) {
                                                    echo $dishData['name'] . ' : ';
                                                    echo $dishContainsData['quantity'] . '<br>';
                                                    if ($dishContainsData['quantity'] > $dishData['quantity']) {
                                                        $verifyQuantityDish = 0;
                                                    }
                                                }
                                            }
                                        }
                                        echo '<article name="quantityMax">';
                                            echo '<input type="hidden" name="quantityVerify' . $menuData['name'] . '" value="' . $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']] . '">';
                                        echo '</article>';
                                        echo '<select name="' . $menuData['name'] . '">';
                                        for($i = 0; $i <= $arrayQuantityVerify[$dishContainsData['idDish'] . $menuData['id']]; $i++) {
                                            echo '<option value="' . $i . '">' . $i . '</option>';
                                        }
                                        echo '</select>';
                                        echo '</form>';
                                    }
                                }
                            } else {
                                $emptyMenu = 1;
                            }
                        }
                        if($emptyMenu == 1) {
                            echo '<center>';
                                echo '<label>The stock is empty</label> <br>';
                            echo '</center>';
                        }
                        $reqMenu->closeCursor();
                        echo '</div>';
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong>There is no product</strong>
                        </div>
                        <?php
                    }
                    ?>
                </article>
                <article class="col-lg-6 radius" style="border-right-width: 0;">
                    <?php

                    $reqDish = $db->query('SELECT * FROM DISH WHERE idFranchisee = ' . $_SESSION["idFranchisee"]);

                    if(($dishVerify = $reqDish->fetchALL()) != NULL) {
                        echo '<div class="col" id="result">';
                        foreach($dishVerify as $dishData) {
                            if ($dishData['quantity'] > 0) {
                                $emptyDishBis = 1;
                                $reqIngredientContains = $db->query('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = ' . $dishData["id"]);
                                if (($ingredientContainsVerify = $reqIngredientContains->fetchALL()) != NULL) {
                                    echo '<form method="post" name="dish" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                    echo '<label>Dish :</label> <br>';
                                    echo $dishData['name'] . '<br>';
                                    echo $dishData['quantity'] . ' left<br>';
                                    echo $dishData['price'] . ' $<br>';
                                    echo '<input type="hidden" name="productId" value="' . htmlspecialchars($dishData['id']) . '">';
                                    echo '<label>Ingredient :</label> <br>';
                                    foreach ($ingredientContainsVerify as $ingredientContainsData) {
                                        $reqIngredient = $db->query('SELECT * FROM INGREDIENT WHERE id = ' . $ingredientContainsData["idIngredient"]);
                                        if (($ingredientVerify = $reqIngredient->fetchALL()) != NULL) {
                                            foreach ($ingredientVerify as $ingredientData) {
                                                echo $ingredientData['name'] . ' : ';
                                                echo $ingredientContainsData['quantity'] . '<br>';
                                            }
                                        }
                                    }
                                    echo '<select name="' . $dishData['name'] . '">';
                                    for ($i = 0; $i <= $dishData['quantity']; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    echo '</select>';
                                    echo '</form>';
                                } else {
                                    echo '<form method="post" name="dish" class="radius" style="background-color: rgb(241, 171, 64); padding: 20px; margin-top: 10px; margin-bottom: 10px">';
                                    echo '<label>Dish :</label> <br>';
                                    echo $dishData['name'] . '<br>';
                                    echo $dishData['quantity'] . ' left<br>';
                                    echo $dishData['price'] . ' $<br>';
                                    //echo '<input type="hidden" name="productId" value="' . htmlspecialchars($dishData['id']) . '">';
                                    echo '<select name="' . $dishData['name'] . '">';
                                    for ($i = 0; $i <= $dishData['quantity']; $i++) {
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                    echo '</select>';
                                    echo '</form>';
                                }
                            } else {
                                $emptyDish = 1;
                            }
                        }
                        if($emptyDish == 1 AND !isset($emptyDishBis)) {
                            echo '<center>';
                            echo '<label>The stock is empty</label> <br>';
                            echo '</center>';
                        }

                        $reqDish->closeCursor();
                        echo '</div>';
                    } else {
                        ?>
                        <div style='font-size: 20px; text-align: center;'>
                            <strong>There is no product named as follows</strong>
                        </div>
                        <?php
                    }
                    ?>
                </article>
            </section>
        </div>
        <script>
            window.addEventListener("DOMContentLoaded", () => {
                const btn = document.querySelector('#btn');
                btn.addEventListener('click', event => {

                    const dish = document.getElementsByName('dish');
                    var dishArray = [];

                    //on boucle pour chercher tous les produits que l'utilisateur a choisi.
                    for(let i = 0; i < dish.length; i++){
                        const dishSelect = dish[i].lastChild;
                        const choice = dishSelect.selectedIndex;
                        if(dishSelect.options[choice].value > 0){
                            dishArray.push(dishSelect.name);
                            dishArray.push(dishSelect.options[choice].value);
                        }
                    }

                    //on transforme le tableau en chaîne de caractère pour pouvoir l'envoyer en POST.
                    dishArray = JSON.stringify(dishArray);

                    const menu = document.getElementsByName('menu');
                    const quantityMax = document.getElementsByName('quantityMax');
                    var menuArray = [];

                    //on boucle pour chercher tous les produits que l'utilisateur a choisi.
                    for(let i = 0; i < menu.length; i++){
                        const menuSelect = menu[i].lastChild;
                        const quantityMaxSelect = quantityMax[i].lastChild;
                        const choiceMenu = menuSelect.selectedIndex;
                        if(menuSelect.options[choiceMenu].value > 0){
                            menuArray.push(menuSelect.name);
                            menuArray.push(menuSelect.options[choiceMenu].value);
                            menuArray.push(quantityMaxSelect.value);
                        }
                    }

                    //on transforme le tableau en chaîne de caractère pour pouvoir l'envoyer en POST.
                    menuArray = JSON.stringify(menuArray);

                    //on fait la requête seulement si le tableau est rempli c'est à dire supérieur à deux caractères -> [].

                    if(dishArray.length > 2 && menuArray.length > 2) {
                        $.ajax({
                            type: 'POST',
                            url: "../extensions/insertOrderrClient.php",
                            data: {
                                dishArray: dishArray,
                                menuArray: menuArray,
                            },

                            success: output => {
                                window.location.replace('shopPaymentClient.php');
                            }
                        })
                    } else if(dishArray.length > 2) {
                        $.ajax({
                            type: 'POST',
                            url: "../extensions/insertOrderrClient.php",
                            data: {
                                dishArray: dishArray,
                            },

                            success: output => {
                                window.location.replace('shopPaymentClient.php');
                            }
                        })
                    } else if(menuArray.length > 2) {
                        $.ajax({
                            type: 'POST',
                            url: "../extensions/insertOrderrClient.php",
                            data: {
                                menuArray: menuArray,
                            },

                            success: output => {
                                console.log(output);
                                window.location.replace('shopPaymentClient.php');
                            }
                        })
                    };
                });
            });
        </script>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>