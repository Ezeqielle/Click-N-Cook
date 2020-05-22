<?php
/*session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
*/
	require('../extensions/header.php');
?>	
<script src="../js/shopPayment.js" type="text/javascript"></script>
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
							$reqOrder = $db->prepare('SELECT * FROM CONTAINSIN, PURCHASE, ITEM WHERE idFranchisee = :currentId AND CONTAINSIN.idPurchase = PURCHASE.bill_number AND CONTAINSIN.idItem = ITEM.id AND date IS NULL');
							$reqOrder->execute(array(
								'currentId' => /*$_SESSION['id']*/10
							));
							while($orderData = $reqOrder->fetch()) {
								echo '<tr>';
									echo '<td>' . $orderData['name'] . '</td>';
									echo '<td>'	. $orderData['price'] . '$</td>';
									echo '<td>' . $orderData['quantity'] . '</td>';
									echo '<td>' . $orderData['quantity'] * $orderData['price'] . '$</td>';
									$totalPrice += $orderData['quantity'] * $orderData['price'];
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
	include('../extensions/footer.php');

/*} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	header('Location: login/login.php');
	exit;
}*/
?>