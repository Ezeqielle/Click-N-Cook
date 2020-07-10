<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {


    require_once "../bdd/connection.php";
    $db = connectDB();
    
    if(isset($_POST['productsArray'])) {
        $productsArray = json_decode($_POST['productsArray']);

        $reqLastBill = $db->prepare('SELECT * FROM PURCHASE WHERE idFranchisee = :currentId AND date IS NULL');
        $reqLastBill->execute(array(
            'currentId' => $_SESSION['id']
        ));
        $lastBill = $reqLastBill->fetch();

        if($reqLastBill->rowCount() == 1) {
            $deleteOrders = $db->prepare('DELETE FROM CONTAINSIN WHERE idPurchase = :purchaseId');
            $deleteOrders->execute(array(
                'purchaseId' => $lastBill['bill_number']
            ));

            $deleteNotUseBill = $db->prepare('DELETE FROM PURCHASE WHERE bill_number = :lastBill');
            $deleteNotUseBill->execute(array(
                'lastBill' => $lastBill['bill_number']
            ));
        }

        $createBill = $db->prepare('INSERT INTO PURCHASE(idFranchisee) VALUES(:currentId)');
        $createBill->execute(array(
            'currentId' => $_SESSION['id']
        ));

        $billNumber = $db->lastInsertId();

        for($i = 0; $i < count($productsArray); $i += 2) {
            $reqProduct = $db->prepare('SELECT * FROM ITEM WHERE name = :name');
            $reqProduct->execute(array(
                'name' => htmlspecialchars($productsArray[$i])
            ));
            $productId = $reqProduct->fetch();

            $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productId["id"]);
            $quantityData = $reqQuantityProduct->fetch();

            if($reqProduct->rowCount() == 1) {

                //on vérifie que l'utilisateur n'a pas modifier le code HTML avec une quantité inexistante.
                if($productsArray[$i + 1] > 0 AND $productsArray[$i + 1] <= $quantityData['quantity']) {

                    $createOrder = $db->prepare('INSERT INTO CONTAINSIN VALUES(:idPurchase, :idItem, :quantity, :idWarehouse)');
                    $createOrder->execute(array(
                        'idPurchase' => $billNumber,
                        'idItem' => $productId['id'],
                        'quantity' => htmlspecialchars($productsArray[$i + 1]),
                        'idWarehouse' => $quantityData['idWarehouse']
                    ));
                }
            }
        }
    }
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>