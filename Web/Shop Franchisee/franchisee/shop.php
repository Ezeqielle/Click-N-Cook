<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
	
	require('header.php');
?>
<script type="text/javascript" src="js/shop.js"></script>
<main>
	<div class="row">
		<div class="col-lg-3">
			<?php
			include('extensions/profileSection.php');
			?>
		</div>
		<section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12">
					<input type="text" name="search" class="form-control search" id="searchShop" placeholder="Search">
					<button type="button" id="buy" class="btn btn-default btn-sm">BUY</button>
					<?php
					$reqExistOrder = $db->prepare('SELECT * FROM PURCHASE WHERE user = :currentId AND date IS NULL');
					$reqExistOrder->execute(array(
						'currentId' => $_SESSION['id']
					));
					if($reqExistOrder->rowCount() > 0) {
						echo '<a href="shopPayment.php" class="btn btn-default btn-sm">Last Bill</a>';
					}
					if(isset($_GET['payment']) AND !empty($_GET['payment']) AND $_GET['payment'] == 'success') {
						echo '<div class="alert alert-success alert-dismissible">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
 								<strong>Success!</strong> Your payment was successfully.
							</div>';
					}
					?>
					
				</article>
			</div>
			<?php
			$reqProduct = $db->query('SELECT name, stock, price FROM PRODUCT WHERE product_status = true AND stock > 0');
			
			echo '<div class="row" id="result">';
			while($productData = $reqProduct->fetch()) {
				
				echo '<article name="products" class="col-lg-4" id="products">';
					echo $productData['name'] . '<br>';
					echo $productData['stock'] . ' left<br>';
					echo $productData['price'] . ' $<br>';
  					echo '<select name="' . $productData['name'] . '">';
  						for($i = 0; $i <= $productData['stock']; $i++) {
  							echo '<option value="' . $i . '">' . $i . '</option>';
  						}
  					echo '</select>';
				echo '</article>';
			}
			$reqProduct->closeCursor();
			echo '</div>';
			?>

		</section>
		<section class="col-lg-3">
			<?php
			include('extensions/chat.php');
			?>
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