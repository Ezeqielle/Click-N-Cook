<?php
//session_start();
//if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1 AND isset($_GET['id'])){

	require('headerAdmin.php');
	if(isset($_POST['validate'])){

		if($_POST['price'] > 0){

			if($_POST['quantity'] > 0){

				$reqUser = $db->prepare('UPDATE PRODUCT SET name = :name , price = :price , stock = :stock , product_status = :product_status WHERE id = :previousId');
				$reqUser->execute(array(
					'name' => $_POST['name'],
					'price' => $_POST['price'],
					'stock' => $_POST['stock'],
					'product_status' => $_POST['product_status'],
					'previousId' => $_GET['id']
				));
				header('Location: shopAdmin.php');
				exit;

			} else {
				$error = 'Stock must be over 0 !';
			}
		} else {
			$error = 'Price must be over 0$ !';
		}
	}
?>
<main>
	<div class="row">
		<section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12">
					<?php
					$reqProduct = $db->prepare('SELECT * FROM PRODUCT WHERE id = :id');
					$reqProduct->execute(array(
						'id' => $_GET['id']
					));
					$productData = $reqProduct->fetch();

					echo '<form method="post">';
						echo '<label>Name :</label><input type="" name="name" value="' . $productData['name'] . '" class="form-control">';
						echo '<label>Price :</label><input type="" name="price" value="' . $productData['price'] . '" class="form-control">';
						echo '<label>Stock :</label><input type="" name="stock" value="' . $productData['stock'] . '" class="form-control">';
						echo '<label>Product status :</label><select name="product_status" class="form-control">';
							echo '<option value="1" ';
							if($productData['product_status'] == true) { echo 'selected'; }
							echo '>Displayed</option>';
							echo '<option value="0" ';
							if($productData['product_status'] == false) { echo 'selected'; }
							echo '>Hidden</option>';
						echo '</select>';
						echo '<button type="submit" name="validate" class="btn btn-default btn-sm">Validate</button>';
					echo '</form>';
					if(isset($error)){
						echo '<font color="red">'. $error ."</font>";
					}
					?>
				</article>
			</div>
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