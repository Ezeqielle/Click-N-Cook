<?php
session_start();

if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

	require('../extensions/headerFranchisee.php');
?>	
<script src="/js/shopPayment.js" type="text/javascript"></script>
<main>
	<div class="row">
		<section class="col-lg-12"id="bill">
			<div class="row">
				<article class="col-lg-12 radius">
					<table class="table table-bordered table-striped">
						<thead>
					      <tr>
					        <th><?php echo TXT_CLIENT_PR; ?></th>
					        <th><?php echo TXT_CLIENT_UP; ?></th>
					        <th><?php echo TXT_REGISTER_PLACEHOLDER5; ?></th>
					        <th><?php echo TXT_CLIENT_AMOUNT; ?></th>
					        <th><?php echo TXT_CLIENT_PWA; ?></th>
					      </tr>
					    </thead>
					    <tbody>
							<h1><?php echo TXT_CLIENT_BILLS; ?></h1>
							<?php
                            $totalPrice = 0;
							$reqOrder = $db->prepare('SELECT * FROM CONTAINSIN, PURCHASE, ITEM WHERE idFranchisee = :currentId AND CONTAINSIN.idPurchase = PURCHASE.bill_number AND CONTAINSIN.idItem = ITEM.id AND date IS NULL');
							$reqOrder->execute(array(
								'currentId' => $_SESSION['id']
							));
							while($orderData = $reqOrder->fetch()) {

								$reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$orderData["idWarehouse"]);
                				$warehouseData = $reqWarehouse->fetch();
								echo '<tr>';
									echo '<td>' . $orderData['name'] . '</td>';
									echo '<td>'	. number_format($orderData['price'] + (($orderData['price'] * 10) / 100), 2) . TXT_CLIENT_MONNEY . '</td>';
									echo '<td>' . $warehouseData['address'] . '</td>';
									echo '<td>' . $orderData['quantity'] . '</td>';
									echo '<td>' . number_format($orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100)), 2) . TXT_CLIENT_MONNEY . '</td>';
									$totalPrice += $orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100));
								echo '</tr>';
							}
							?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><strong><?php echo TXT_CLIENT_TOTAL . number_format($totalPrice, 2) . TXT_CLIENT_MONNEY; ?></strong></td>
							</tr>
						</tbody>
					</table>
					<button type="button" class="btn btn-default btn-sm" id="buy"><?php echo TXT_CLIENT_BUY; ?></button>
					<a href="shop.php" class="btn btn-default btn-sm"><?php echo TXT_CLIENT_CANCEL; ?></a>
				</article>
			</div>
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