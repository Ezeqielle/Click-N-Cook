<?php
/*session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1){
	*/
	require('headerAdmin.php');
	/*
	if(isset($_POST['delete'])){
		$reqProduct = $db->prepare('DELETE FROM PRODUCT WHERE id = :id');
		$reqProduct->execute(array(
			'id' => $_POST['productId']
		));
	}*/
?>
<main>
	<div class="row">
		<div class="col-lg-3">
			<?php
			//include('../extensions/profileSection.php');
			?>
		</div>
		<section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12">
					<input type="text" name="search" class="form-control search" id="searchShopAdmin" placeholder="Search">
					<script type="text/javascript" src="../js/export.js"></script>
					<a role="button" href="shopAddProduct.php" class="btn btn-default btn-sm">
						Add a new product
					</a>
					<button type="button" id="download" class="btn btn-default btn-sm">
						Download CSV file
					</button>
				</article>
			</div>
			<?php
			$reqProduct = $db->query('SELECT * FROM ITEM');

			echo '<div class="row" id="result">';
			while($productData = $reqProduct->fetch()){
                $reqQuantityProduct = $db->query('SELECT quantity FROM BELONGIN WHERE idItem = ' .$productData["id"]);
                $quantityData = $reqQuantityProduct->fetch();

				echo '<article class="col-lg-4" id="products">';
					echo '<form method="post">';
						echo $productData['product_status'] == 0 ? '<font color="red"><strong>HIDDEN</strong></font><br>' : '';
                        echo $productData['name'] . '<br>';
                        echo $quantityData['quantity'] . ' left<br>';
                        echo $productData['price'] . ' $<br>';
                        echo '<input type="hidden" name="productId" value="' . htmlspecialchars($productData['id']) . '">';
                        echo '<button type="submit" name="delete" class="btn btn-default btn-sm">Delete</button><br>';
                        echo '<a class="btn btn-default btn-sm" href="shopModifyProduct.php?id=' . $productData['id'] . '">Modify</a>';
                    echo '</form>';
                echo '</article>';
			}
			$reqProduct->closeCursor();
			echo '</div>';
			?>
		</section>
		<section class="col-lg-3">
			<?php
			//include('../extensions/chat.php');
			?>
		</section>
	</div>
</main>
<?php
	include('../extensions/footer.php');

/*} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	header('Location: ../home.php');
	exit;
}*/
?>