<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1 AND isset($_GET['id'])){

	require('headerAdmin.php');
	if(isset($_POST['validate'])){

		if($_POST['price'] > 0){

			if($_POST['quantity'] > 0){

                $reqProduct = $db->prepare('UPDATE ITEM SET name = :name , price = :price , product_status = :product_status, dish = :dish WHERE id = :previousId');
                $reqProduct->execute(array(
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'product_status' => $_POST['product_status'],
                    'dish' => $_POST['typeOfFood'],
                    'previousId' => $_GET['id']
                ));

                $reqQuantityProduct = $db->prepare('UPDATE BELONGIN SET quantity = :quantity WHERE idItem = :previousId');
                $reqQuantityProduct->execute(array(
                    'quantity' => $_POST['quantity'],
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
		<section class="col-lg-3">
        </section>
        <section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12 radius">
					<?php
					$reqProduct = $db->prepare('SELECT * FROM ITEM, BELONGIN WHERE id = :id');
					$reqProduct->execute(array(
						'id' => $_GET['id']
					));
					$productData = $reqProduct->fetch();

                    $reqQuantityProduct = $db->prepare('SELECT * FROM BELONGIN WHERE iditem = :id');
                    $reqQuantityProduct->execute(array(
                        'id' => $_GET['id']
                    ));
                    $quantityData = $reqQuantityProduct->fetch();

                    $reqWarehouse = $db->prepare('SELECT * FROM WAREHOUSE WHERE id = :id');
                    $reqWarehouse->execute(array(
                        'id' => $quantityData['idWarehouse']
                    ));
                    $warehouseData = $reqWarehouse->fetch();

					echo '<form method="post">';
						echo '<label>Name :</label><input type="" name="name" value="' . $productData['name'] . '" class="form-control">';
						echo '<label>Price :</label><input type="" name="price" value="' . $productData['price'] . '" class="form-control">';
						echo '<label>Quantity :</label><input type="" name="quantity" value="' . $quantityData['quantity'] . '" class="form-control">';
						echo '<label>Product status :</label><select name="product_status" class="form-control">';
							echo '<option value="1" ';
							if($productData['product_status'] == true) { echo 'selected'; }
							echo '>Displayed</option>';
							echo '<option value="0" ';
							if($productData['product_status'] == false) { echo 'selected'; }
							echo '>Hidden</option>';
						echo '</select>';
                        echo '<label>Warehouse :</label><select name="warehouse" class="form-control">';
                            echo '<option value=' . $warehouseData["address"]. '>' . $warehouseData["address"] . '</option>';
                        echo '</select>';
                        echo '<label>Type of food :</label><select name="typeOfFood" class="form-control">';
                            echo '<option value="1" ';
                            if($productData['dish'] == true) { echo 'selected'; }
                            echo '>Dish</option>';
                            echo '<option value="0" ';
                            if($productData['dish'] == false) { echo 'selected'; }
                            echo '>Ingredient</option>';
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
        <section class="col-lg-3">
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