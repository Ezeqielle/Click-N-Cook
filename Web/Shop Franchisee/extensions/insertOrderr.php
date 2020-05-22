<?php
/*session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
*/


require_once "../bdd/connection.php";
$db = connectDB();

	if(isset($_POST['productArray'])) {
	    ?>
        <script>
            console.log("ok");
        </script>
        <?php
		$productsArray = json_decode($_POST['vals']);

		$reqLastBill = $db->prepare('SELECT * FROM PURCHASE WHERE idFranchisee = :currentId AND date IS NULL');
		$reqLastBill->execute(array(
			'currentId' => /*$_SESSION['id']*/10
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
			'currentId' => /*$_SESSION['id']*/10
		));

		$billNumber = $db->lastInsertId();

		for($i = 0; $i < count($productsArray); $i += 2) {
			$reqProduct = $db->prepare('SELECT * FROM ITEM WHERE name = :name');
			$reqProduct->execute(array(
				'name' => htmlspecialchars($productsArray[$i])
			));
			$productId = $reqProduct->fetch();

			if($reqProduct->rowCount() == 1) {

				//on vérifie que l'utilisateur n'a pas modifier le code HTML avec une quantité inexistante.
				if($productsArray[$i + 1] > 0 AND $productsArray[$i + 1] <= $productId['stock']) {

					$createOrder = $db->prepare('INSERT INTO CONTAINSIN VALUES(:idPurchase, :idItem, :quantity)');
					$createOrder->execute(array(
						'idPurchase' => $billNumber,
						'idItem' => $productId['id'],
						'quantity' => htmlspecialchars($productsArray[$i + 1])
					));
				}
			}
		}
	} else {
        ?>
        <script>
            console.log("ko");
        </script>
        <?php
    }
?>