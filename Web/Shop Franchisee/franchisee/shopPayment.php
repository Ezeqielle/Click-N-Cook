<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

	require('header.php');
	if(isset($_POST['productsArray'])) {
		$productsArray = json_decode($_POST['productsArray']);

		$reqLastBill = $db->prepare('SELECT * FROM PURCHASE WHERE user = :currentId AND date IS NULL');
		$reqLastBill->execute(array(
			'currentId' => $_SESSION['id']
		));
		$lastBill = $reqLastBill->fetch();

		if($reqLastBill->rowCount() == 1) {
			$deleteOrders = $db->prepare('DELETE FROM ORDERR WHERE purchase = :purchaseId');
			$deleteOrders->execute(array(
				'purchaseId' => $lastBill['bill_number']
			));

			$deleteNotUseBill = $db->prepare('DELETE FROM PURCHASE WHERE bill_number = :lastBill');
			$deleteNotUseBill->execute(array(
				'lastBill' => $lastBill['bill_number']
			));
		}

		$createBill = $db->prepare('INSERT INTO PURCHASE(user) VALUES(:currentId)');
		$createBill->execute(array(
			'currentId' => $_SESSION['id']
		));

		$billNumber = $db->lastInsertId();

		for($i = 0; $i < count($productsArray); $i += 2) {
			$reqProduct = $db->prepare('SELECT * FROM PRODUCT WHERE name = :name');
			$reqProduct->execute(array(
				'name' => htmlspecialchars($productsArray[$i])
			));
			$productId = $reqProduct->fetch();

			if($reqProduct->rowCount() == 1) {

				//on vérifie que l'utilisateur n'a pas modifier le code HTML avec une quantité inexistante.
				if($productsArray[$i + 1] > 0 AND $productsArray[$i + 1] <= $productId['stock']) {

					$createOrder = $db->prepare('INSERT INTO ORDERR VALUES(:billNumber, :productId, :amount)');
					$createOrder->execute(array(
						'billNumber' => $billNumber,
						'productId' => $productId['id'],
						'amount' => htmlspecialchars($productsArray[$i + 1])
					));
				}
			}
		}
	}
?>
<script src="js/shopPayment.js" type="text/javascript"></script>
<main>
	<div class="row">
		<section class="col-lg-12">
			<div class="row">
				<article class="col-lg-12" id="bill">
					<table class="table table-bordered table-striped">
						<thead>
					      <tr>
					        <th>Product</th>
					        <th>Unit price</th>
					        <th>Amount</th>
					        <th>Price with amount</th>
					      </tr>
					    </thead>
					    <tbody>
							<h1>Bill</h1>
							<?php
							$totalPrice = 0;
							$reqOrder = $db->prepare('SELECT * FROM ORDERR, PURCHASE, PRODUCT WHERE user = :currentId AND ORDERR.purchase = PURCHASE.bill_number AND ORDERR.product = PRODUCT.id AND date IS NULL');
							$reqOrder->execute(array(
								'currentId' => $_SESSION['id']
							));
							while($orderData = $reqOrder->fetch()) {
								echo '<tr>';
									echo '<td>' . $orderData['name'] . '</td>';
									echo '<td>'	. $orderData['price'] . '$</td>';
									echo '<td>' . $orderData['amount'] . '</td>';
									echo '<td>' . $orderData['amount'] * $orderData['price'] . '$</td>';
									$totalPrice += $orderData['amount'] * $orderData['price'];
								echo '</tr>';
							}
							?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td><strong><?php echo 'Total price : ' . $totalPrice . '$';?></strong></td>
							</tr>
						</tbody>
					</table>
					<button type="button" class="btn btn-default btn-sm" id="buy">BUY</button>
					<a href="shop.php" class="btn btn-default btn-sm">Cancel</a>
				</article>
			</div>
		</section>
	</div>
</main>
<?php
	include('footer.php');

} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	header('Location: login/login.php');
	exit;
}
?>