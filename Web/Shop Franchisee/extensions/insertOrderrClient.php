<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 2) {

    require_once "../bdd/connection.php";
    $db = connectDB();

    if(isset($_POST['dishArray']) || isset($_POST['menuArray'])) {

        $reqLastBill = $db->prepare('SELECT * FROM PURCHASECLIENT WHERE idClient = :currentId AND date IS NULL');
        $reqLastBill->execute(array(
            'currentId' => $_SESSION['id']
        ));
        $lastBill = $reqLastBill->fetch();

        /**DELETE*/
        if ($reqLastBill->rowCount() == 1) {
            $reqContainsDishSaleClient = $db->prepare('SELECT * FROM CONTAINSDISHSALECLIENT WHERE idPurchaseClient = :id');
            $reqContainsDishSaleClient->execute(array(
                'id' => $lastBill['bill_numberClient']
            ));

            while($containsDishSaleClientData = $reqContainsDishSaleClient->fetch()) {
                $reqDishClient = $db->prepare('SELECT * FROM DISHCLIENT WHERE id = :id');
                $reqDishClient->execute(array(
                    'id' => $containsDishSaleClientData['idDishClient']
                ));
                $dishClientData = $reqDishClient->fetch();

                $reqContainsIngredientsDishClient = $db->prepare('SELECT * FROM CONTAINSINGREDIENTSDISHCLIENT WHERE idDishClient = :id');
                $reqContainsIngredientsDishClient->execute(array(
                    'id' => $dishClientData['id']
                ));

                $delContainsIngredientsDishClient = $db->prepare('DELETE FROM CONTAINSINGREDIENTSDISHCLIENT WHERE idDishClient = :id');
                $delContainsIngredientsDishClient->execute(array(
                    'id' => $dishClientData['id']
                ));

                while($containsIngredientsDishClientData = $reqContainsIngredientsDishClient->fetch()) {
                    $delIngredientClient = $db->prepare('DELETE FROM INGREDIENTCLIENT WHERE id = :id');
                    $delIngredientClient->execute(array(
                        'id' => $containsIngredientsDishClientData['idIngredientClient']
                    ));
                }

                $deleteOrders = $db->prepare('DELETE FROM CONTAINSDISHSALECLIENT WHERE idPurchaseClient = :purchaseId');
                $deleteOrders->execute(array(
                    'purchaseId' => $lastBill['bill_numberClient']
                ));

                $delDishClient = $db->prepare('DELETE FROM DISHCLIENT WHERE id = :id');
                $delDishClient->execute(array(
                    'id' => $containsDishSaleClientData['idDishClient']
                ));
            }

            $reqContainsMenuSaleClient = $db->prepare('SELECT * FROM CONTAINSMENUSALECLIENT WHERE idPurchaseClient = :id');
            $reqContainsMenuSaleClient->execute(array(
                'id' => $lastBill['bill_numberClient']
            ));

            /**DELETE*/
            while($containsMenuSaleClientData = $reqContainsMenuSaleClient->fetch()) {

                $reqMenuClient = $db->prepare('SELECT * FROM MENUCLIENT WHERE id = :id');
                $reqMenuClient->execute(array(
                    'id' => $containsMenuSaleClientData['idMenuClient']
                ));
                $menuClientData = $reqMenuClient->fetch();

                $reqContainsDishMenuClient = $db->prepare('SELECT * FROM CONTAINSDISHMENUCLIENT WHERE idMenuClient = :id');
                $reqContainsDishMenuClient->execute(array(
                    'id' => $menuClientData['id']
                ));

                $delContainsDishMenuClient = $db->prepare('DELETE FROM CONTAINSDISHMENUCLIENT WHERE idMenuClient = :id');
                $delContainsDishMenuClient->execute(array(
                    'id' => $menuClientData['id']
                ));

                while($containsDishMenuClientData = $reqContainsDishMenuClient->fetch()) {
                    $reqDishMenuClient = $db->prepare('SELECT * FROM DISHCLIENT WHERE id = :id');
                    $reqDishMenuClient->execute(array(
                        'id' => $containsDishMenuClientData['idDishClient']
                    ));
                    $dishMenuClientData = $reqDishMenuClient->fetch();

                    $reqContainsIngredientsDishMenuClient = $db->prepare('SELECT * FROM CONTAINSINGREDIENTSDISHCLIENT WHERE idDishClient = :id');
                    $reqContainsIngredientsDishMenuClient->execute(array(
                        'id' => $dishMenuClientData['id']
                    ));

                    $delContainsIngredientsDishMenuClient = $db->prepare('DELETE FROM CONTAINSINGREDIENTSDISHCLIENT WHERE idDishClient = :id');
                    $delContainsIngredientsDishMenuClient->execute(array(
                        'id' => $dishMenuClientData['id']
                    ));

                    while($containsIngredientsDishMenuClientData = $reqContainsIngredientsDishMenuClient->fetch()) {
                        $delIngredientMenuClient = $db->prepare('DELETE FROM INGREDIENTCLIENT WHERE id = :id');
                        $delIngredientMenuClient->execute(array(
                            'id' => $containsIngredientsDishMenuClientData['idIngredientClient']
                        ));
                    }

                    $delDishClient = $db->prepare('DELETE FROM DISHCLIENT WHERE id = :id');
                    $delDishClient->execute(array(
                        'id' => $containsDishMenuClientData['idDishClient']
                    ));
                }

                $deleteOrders = $db->prepare('DELETE FROM CONTAINSMENUSALECLIENT WHERE idPurchaseClient = :purchaseId');
                $deleteOrders->execute(array(
                    'purchaseId' => $lastBill['bill_numberClient']
                ));

                $delDishClient = $db->prepare('DELETE FROM MENUCLIENT WHERE id = :id');
                $delDishClient->execute(array(
                    'id' => $containsMenuSaleClientData['idMenuClient']
                ));
            }

            $deleteNotUseBill = $db->prepare('DELETE FROM PURCHASECLIENT WHERE bill_numberClient = :lastBill');
            $deleteNotUseBill->execute(array(
                'lastBill' => $lastBill['bill_numberClient']
            ));
        }

        $createBill = $db->prepare('INSERT INTO PURCHASECLIENT(idClient) VALUES(:currentId)');
        $createBill->execute(array(
            'currentId' => $_SESSION['id']
        ));

        $billNumber = $db->lastInsertId();

        $verifyQuantityDish = 1;


        if(isset($_POST['dishArray']) AND isset($_POST['menuArray'])) {
            $dishArray = json_decode($_POST['dishArray']);

            $arrayVerifyDish[] = NULL;
            $arrayVerifyMenu[] = NULL;

            for($i = 0; $i < count($dishArray); $i += 2) {
                $reqProduct = $db->prepare('SELECT * FROM DISH WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqProduct->execute(array(
                    'name' => htmlspecialchars($dishArray[$i]),
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $productIdVerify = $reqProduct->fetch();

                $arrayVerifyDish[$i] = $productIdVerify['id'];
                $arrayVerifyDish[$i + 1] = $dishArray[$i + 1];
                $arrayVerifyDish[$i + 2] = $productIdVerify['quantity'];
            }

            $menuArray = json_decode($_POST['menuArray']);

            for($i = 0; $i < count($menuArray); $i += 3) {
                $reqProductMenu = $db->prepare('SELECT * FROM MENU WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqProductMenu->execute(array(
                    'name' => htmlspecialchars($menuArray[$i]),
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $productMenuIdVerify = $reqProductMenu->fetch();

                $reqContainsDishMenu = $db->prepare('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = :idMenu');
                $reqContainsDishMenu->execute(array(
                    'idMenu' => $productMenuIdVerify['id']
                ));

                while($containsDishMenuData = $reqContainsDishMenu->fetch()) {
                    $arrayVerifyMenu[$i] = $containsDishMenuData['idDish'];
                    $arrayVerifyMenu[$i + 1] = $containsDishMenuData['quantity'] * $menuArray[$i + 1];
                }
            }
            for($i = 0; $i < count($arrayVerifyDish); $i += 3) {
                for($j = 0; $j < count($arrayVerifyMenu); $j += 2) {
                    if($arrayVerifyDish[$i] == $arrayVerifyMenu[$j]) {
                        if($arrayVerifyDish[$i + 1] + $arrayVerifyMenu[$j + 1] > $arrayVerifyDish[$i + 2]) {
                            return 0;
                        }
                    }
                }
            }
        }

        /**ADD FOR DISH*/
        if(isset($_POST['dishArray'])) {
            $dishArray = json_decode($_POST['dishArray']);

            for($i = 0; $i < count($dishArray); $i += 2) {
                $reqProduct = $db->prepare('SELECT * FROM DISH WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqProduct->execute(array(
                    'name' => htmlspecialchars($dishArray[$i]),
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $productId = $reqProduct->fetch();

                if($reqProduct->rowCount() == 1) {

                    //on vérifie que l'utilisateur n'a pas modifier le code HTML avec une quantité inexistante.
                    if($dishArray[$i + 1] > 0 AND $dishArray[$i + 1] <= $productId['quantity']) {

                        $reqAdvantageVerify = $db->prepare('SELECT * FROM ADVANTAGE WHERE idClient = :idClient AND idFranchisee = :idFranchisee');
                        $reqAdvantageVerify->execute(array(
                            'idClient' => $_SESSION['id'],
                            'idFranchisee' => $_SESSION['idFranchisee']
                        ));
                        $advantageData = $reqAdvantageVerify->fetch();

                        if($reqAdvantageVerify->rowCount() > 0) {
                            $price = $productId['price'] - (($productId['price'] * $advantageData['advantage']) / 100);
                            $addDishClient = $db->prepare('INSERT INTO DISHCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                            $addDishClient->execute(array(
                                'name' => $productId['name'],
                                'price' => number_format($price, 2),
                                'idClient' => $_SESSION['id']
                            ));
                        } else {
                            $addDishClient = $db->prepare('INSERT INTO DISHCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                            $addDishClient->execute(array(
                                'name' => $productId['name'],
                                'price' => $productId['price'],
                                'idClient' => $_SESSION['id']
                            ));
                        }

                        $reqDishClient = $db->prepare('SELECT * FROM DISHCLIENT WHERE name = :name AND idClient = :idClient AND verify is NULL');
                        $reqDishClient->execute(array(
                            'name' => $productId['name'],
                            'idClient' => $_SESSION['id']
                        ));
                        $dishClientData = $reqDishClient->fetch();

                        $reqContainsIngredientDish = $db->prepare('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = :idDish');
                        $reqContainsIngredientDish->execute(array(
                            'idDish' => $productId['id']
                        ));

                        while($containsIngredientDishData = $reqContainsIngredientDish->fetch()) {
                            $reqIngredient = $db->prepare('SELECT * FROM INGREDIENT WHERE id = :idIngredient');
                            $reqIngredient->execute(array(
                                'idIngredient' => $containsIngredientDishData['idIngredient']
                            ));
                            $ingredientData = $reqIngredient->fetch();

                            $addIngredientClient = $db->prepare('INSERT INTO INGREDIENTCLIENT (name, idClient) VALUES(:name, :idClient)');
                            $addIngredientClient->execute(array(
                                'name' => $ingredientData['name'],
                                'idClient' => $_SESSION['id']
                            ));

                            $reqIngredientClient = $db->prepare('SELECT * FROM INGREDIENTCLIENT WHERE name = :name AND idClient = :idClient AND verify is NULL');
                            $reqIngredientClient->execute(array(
                                'name' => $ingredientData['name'],
                                'idClient' => $_SESSION['id']
                            ));
                            $ingredientClientData = $reqIngredientClient->fetch();


                            $addIngredientClient = $db->prepare('INSERT INTO CONTAINSINGREDIENTSDISHCLIENT (idIngredientClient, idDishClient, quantity) VALUES(:idIngredientClient, :idDishClient, :quantity)');
                            $addIngredientClient->execute(array(
                                'idIngredientClient' => $ingredientClientData['id'],
                                'idDishClient' => $dishClientData['id'],
                                'quantity' => $containsIngredientDishData['quantity']
                            ));


                        }


                        $createOrder = $db->prepare('INSERT INTO CONTAINSDISHSALECLIENT (idDishClient, idPurchaseClient, quantity) VALUES(:idDishClient, :idPurchaseClient, :quantity)');
                        $createOrder->execute(array(
                            'idDishClient' => $dishClientData['id'],
                            'idPurchaseClient' => $billNumber,
                            'quantity' => htmlspecialchars($dishArray[$i + 1])
                        ));
                    }
                }
            }
        }

        /**ADD FOR MENU*/
        if(isset($_POST['menuArray'])) {
            $menuArray = json_decode($_POST['menuArray']);

            for ($i = 0; $i < count($menuArray); $i += 3) {
                $reqProductMenu = $db->prepare('SELECT * FROM MENU WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqProductMenu->execute(array(
                    'name' => htmlspecialchars($menuArray[$i]),
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $productMenuId = $reqProductMenu->fetch();

                if ($reqProductMenu->rowCount() == 1) {

                    //on vérifie que l'utilisateur n'a pas modifier le code HTML avec une quantité inexistante.
                    if ($menuArray[$i + 1] > 0 and $menuArray[$i + 1] <= $menuArray[$i + 2]) {
                        $reqAdvantageVerify = $db->prepare('SELECT * FROM ADVANTAGE WHERE idClient = :idClient AND idFranchisee = :idFranchisee');
                        $reqAdvantageVerify->execute(array(
                            'idClient' => $_SESSION['id'],
                            'idFranchisee' => $_SESSION['idFranchisee']
                        ));
                        $advantageData = $reqAdvantageVerify->fetch();

                        if($reqAdvantageVerify->rowCount() > 0) {
                            $price = $productMenuId['price']  - (($productMenuId['price'] * $advantageData['advantage']) / 100);
                            $addMenuClient = $db->prepare('INSERT INTO MENUCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                            $addMenuClient->execute(array(
                                'name' => $productMenuId['name'],
                                'price' => number_format($price, 2),
                                'idClient' => $_SESSION['id']
                            ));
                        } else {
                            $addMenuClient = $db->prepare('INSERT INTO MENUCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                            $addMenuClient->execute(array(
                                'name' => $productMenuId['name'],
                                'price' => $productMenuId['price'],
                                'idClient' => $_SESSION['id']
                            ));
                        }

                        $reqMenuClient = $db->prepare('SELECT * FROM MENUCLIENT WHERE name = :name AND idClient = :idClient AND verify is NULL');
                        $reqMenuClient->execute(array(
                            'name' => $productMenuId['name'],
                            'idClient' => $_SESSION['id']
                        ));
                        $menuClientData = $reqMenuClient->fetch();

                        $reqContainsDishMenu = $db->prepare('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = :idMenu');
                        $reqContainsDishMenu->execute(array(
                            'idMenu' => $productMenuId['id']
                        ));

                        while($containsDishMenuData = $reqContainsDishMenu->fetch()) {
                            echo 'test1</br>';
                            $reqDishMenuClient = $db->prepare('SELECT * FROM DISH WHERE id = :idDish AND idFranchisee = :idFranchisee');
                            $reqDishMenuClient->execute(array(
                                'idDish' => $containsDishMenuData['idDish'],
                                'idFranchisee' => $_SESSION['idFranchisee']
                            ));
                            $dishMenuClientData = $reqDishMenuClient->fetch();


                            $reqAdvantageVerify = $db->prepare('SELECT * FROM ADVANTAGE WHERE idClient = :idClient AND idFranchisee = :idFranchisee');
                            $reqAdvantageVerify->execute(array(
                                'idClient' => $_SESSION['id'],
                                'idFranchisee' => $_SESSION['idFranchisee']
                            ));
                            $advantageData = $reqAdvantageVerify->fetch();

                            if($reqAdvantageVerify->rowCount() > 0) {
                                $price = $dishMenuClientData['price'] - (($dishMenuClientData['price'] * $advantageData['advantage']) / 100);
                                $addDishClient = $db->prepare('INSERT INTO DISHCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                                $addDishClient->execute(array(
                                    'name' => $dishMenuClientData['name'],
                                    'price' => number_format($price, 2),
                                    'idClient' => $_SESSION['id']
                                ));
                            } else {
                                $addDishClient = $db->prepare('INSERT INTO DISHCLIENT (name, price, idClient) VALUES(:name, :price, :idClient)');
                                $addDishClient->execute(array(
                                    'name' => $dishMenuClientData['name'],
                                    'price' => $dishMenuClientData['price'],
                                    'idClient' => $_SESSION['id']
                                ));
                            }
                            /** pas de distinction entre dish ajout dish et dish ajout menu */
                            $idDishClient = $db->lastInsertId();

                            $reqDishClient = $db->prepare('SELECT * FROM DISHCLIENT WHERE id = :id AND name = :name AND idClient = :idClient AND verify is NULL');
                            $reqDishClient->execute(array(
                                'id' => $idDishClient,
                                'name' => $dishMenuClientData['name'],
                                'idClient' => $_SESSION['id']
                            ));
                            $dishClientData = $reqDishClient->fetch();

                            echo 'test2</br>';
                            $reqContainsIngredientDish = $db->prepare('SELECT * FROM CONTAINSINGREDIENTSDISH WHERE idDish = :idDish');
                            $reqContainsIngredientDish->execute(array(
                                'idDish' => $dishMenuClientData['id']
                            ));
                            if(($containsIngredientDishDatas = $reqContainsIngredientDish->fetchALL()) != NULL) {
                                foreach ($containsIngredientDishDatas as $containsIngredientDishData) {
                                    $reqIngredient = $db->prepare('SELECT * FROM INGREDIENT WHERE id = :idIngredient');
                                    $reqIngredient->execute(array(
                                        'idIngredient' => $containsIngredientDishData['idIngredient']
                                    ));
                                    $ingredientData = $reqIngredient->fetch();

                                    $addIngredientClient = $db->prepare('INSERT INTO INGREDIENTCLIENT (name, idClient) VALUES(:name, :idClient)');
                                    $addIngredientClient->execute(array(
                                        'name' => $ingredientData['name'],
                                        'idClient' => $_SESSION['id']
                                    ));

                                    $idIngredientClient = $db->lastInsertId();

                                    $reqIngredientClient = $db->prepare('SELECT * FROM INGREDIENTCLIENT WHERE id = :id AND name = :name AND idClient = :idClient AND verify is NULL');
                                    $reqIngredientClient->execute(array(
                                        'id' => $idIngredientClient,
                                        'name' => $ingredientData['name'],
                                        'idClient' => $_SESSION['id']
                                    ));
                                    $ingredientClientData = $reqIngredientClient->fetch();


                                    $addIngredientClient = $db->prepare('INSERT INTO CONTAINSINGREDIENTSDISHCLIENT (idIngredientClient, idDishClient, quantity) VALUES(:idIngredientClient, :idDishClient, :quantity)');
                                    $addIngredientClient->execute(array(
                                        'idIngredientClient' => $ingredientClientData['id'],
                                        'idDishClient' => $dishClientData['id'],
                                        'quantity' => $containsIngredientDishData['quantity']
                                    ));


                                }
                            }

                            echo 'test3</br>';
                            $createOrderDish = $db->prepare('INSERT INTO CONTAINSDISHMENUCLIENT (idDishClient, idMenuClient, quantity) VALUES(:idDishClient, :idMenuClient, :quantity)');
                            $createOrderDish->execute(array(
                                'idDishClient' => $dishClientData['id'],
                                'idMenuClient' => $menuClientData['id'],
                                'quantity' => $containsDishMenuData['quantity']
                            ));
                            echo 'test4</br>';
                            echo 'test5</br>';
                        }

                        $createOrderMenu = $db->prepare('INSERT INTO CONTAINSMENUSALECLIENT (idMenuClient, idPurchaseClient, quantity) VALUES(:idMenuClient, :idPurchaseClient, :quantity)');
                        $createOrderMenu->execute(array(
                            'idMenuClient' => $menuClientData['id'],
                            'idPurchaseClient' => $billNumber,
                            'quantity' => htmlspecialchars($menuArray[$i + 1])
                        ));
                        echo 'test1';
                    }
                    echo 'test11';
                }
                echo 'test111';
            }
            echo 'test1111';
        }
        echo 'test11111';
    }
    echo 'test111111';
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>