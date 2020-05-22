<?php
/*session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
	*/
	require('../extensions/header.php');
?>
<script type="text/javascript" src="../js/shop.js"></script>
<main>
	<div class="row">
		<section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12">
					<input type="text" name="search" class="form-control search" id="searchShop" placeholder="Search">
					<button type="button" id="buy" class="btn btn-default btn-sm">BUY</button>
					<?php
					$reqExistOrder = $db->prepare('SELECT * FROM PURCHASE WHERE idFranchisee = :currentId AND date IS NULL');
					$reqExistOrder->execute(array(
						'currentId' => /*$_SESSION['id']*/ 10
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
            $reqProduct = $db->query('SELECT * FROM ITEM WHERE product_status = true AND (SELECT quantity FROM BELONGIN WHERE quantity > 0 AND BELONGIN.idItem = ITEM.id)');

            echo '<div class="row" id="result">';
            while($productData = $reqProduct->fetch()){
                $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
                $quantityData = $reqQuantityProduct->fetch();

                $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
                $warehouseData = $reqWarehouse->fetch();

                echo '<article name="products" class="col-lg-4">';
                    echo $productData['name'] . '<br>';
                    echo $quantityData['quantity'] . ' left<br>';
                    echo $productData['price'] . ' $<br>';
                    echo $warehouseData['address'] . '<br>';
                    echo '<select name="' . $productData['name'] . '">';
                        for($i = 0; $i <= $quantityData['quantity']; $i++) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                        }
                    echo '</select>';
                echo '</article>';
            }
            $reqProduct->closeCursor();
            echo '</div>';
            ?>
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