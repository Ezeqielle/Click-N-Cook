<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

	if(isset($_GET['requestForm'])) {

		include('callDatabase.php');
		$reqOrder = $db->prepare('SELECT * FROM ORDERR, PURCHASE, PRODUCT WHERE user = :currentId AND ORDERR.purchase = PURCHASE.bill_number AND ORDERR.product = PRODUCT.id AND date IS NULL');
		$reqOrder->execute(array(
			'currentId' => $_SESSION['id']
		));
		if($reqOrder->rowCount() > 0) {

			$totalPrice = 0;
			while($orderData = $reqOrder->fetch()) {
				$totalPrice += $orderData['amount'] * $orderData['price'];
			}
			echo '
				<form>
					<label>Card number</label><input type="text" id="cardNb" class="form-control">
					<label>Expiry date</label><input type="date" id="expiryDate" class="form-control">
					<label>Security Code</label><input type="text" id="securityCode" maxlength="3" class="form-control">
					<label>Delivery address</label><input type="text" id="address" class="form-control">
					<input type="submit" class="btn btn-default btn-sm" id="submitPayment" value="Pay ' . $totalPrice . '$">
					<button type="button" class="btn btn-default btn-sm" id="cancel">Cancel</button>
				</form>
			';
		} else {
			http_response_code(400);
		}
	}
	if(isset($_GET['cancel'])) {

		include('callDatabase.php');
		$reqOrder = $db->prepare('SELECT * FROM ORDERR, PURCHASE, PRODUCT WHERE user = :currentId AND ORDERR.purchase = PURCHASE.bill_number AND ORDERR.product = PRODUCT.id AND date IS NULL');
		$reqOrder->execute(array(
			'currentId' => $_SESSION['id']
		));
		if($reqOrder->rowCount() > 0) {

			echo '
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
						<h1>Bill</h1>';
						$totalPrice = 0;
						while($orderData = $reqOrder->fetch()) {
							echo '<tr>';
								echo '<td>' . $orderData['name'] . '</td>';
								echo '<td>'	. $orderData['price'] . '$</td>';
								echo '<td>' . $orderData['amount'] . '</td>';
								echo '<td>' . $orderData['amount'] * $orderData['price'] . '$</td>';
								$totalPrice += $orderData['amount'] * $orderData['price'];
							echo '</tr>';
						}
					echo '<tr>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total price : ' . $totalPrice . '$</strong></td>
						</tr>
					</tbody>
				</table>
				<button type="button" class="btn btn-default btn-sm" id="buy">BUY</button>
				<a href="shop.php" class="btn btn-default btn-sm">Cancel</a>
			';
		} else {
			http_response_code(400);
		}
	}
	if(isset($_GET['submitPayment'])) {

		include('callDatabase.php');
		$reqOrder = $db->prepare('SELECT * FROM ORDERR, PURCHASE, PRODUCT WHERE user = :currentId AND ORDERR.purchase = PURCHASE.bill_number AND ORDERR.product = PRODUCT.id AND date IS NULL');
		$reqOrder->execute(array(
			'currentId' => $_SESSION['id']
		));

		if($reqOrder->rowCount() > 0) {
			$totalPrice = 0;
			while($orderData = $reqOrder->fetch()) {
				$totalPrice += $orderData['amount'] * $orderData['price'];
				$reqUpdateStock = $db->prepare('UPDATE PRODUCT SET stock = :stock - :amount WHERE id = :productId');
				$reqUpdateStock->execute(array(
					'stock' => $orderData['stock'],
					'amount' => $orderData['amount'],
					'productId' => $orderData['id']
				));
			}
			$reqUpdateDatePayment = $db->prepare('UPDATE PURCHASE SET date = NOW() WHERE user = :currentId');
			$reqUpdateDatePayment->execute(array(
				'currentId' => $_SESSION['id']
			));
			exit;
		} else {
			http_response_code(400);
		}
	}
} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	//header('Location: ../login/login.php');
	exit;
}