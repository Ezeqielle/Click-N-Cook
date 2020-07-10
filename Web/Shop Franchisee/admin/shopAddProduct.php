<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1){

	require('headerAdmin.php');
	if(isset($_POST['add'])){

		if(isset($_POST['name']) AND isset($_POST['price']) AND isset($_POST['quantity']) AND isset($_POST['product_status']) AND isset($_POST['warehouse']) AND !empty($_POST['name']) AND !empty($_POST['price']) AND !empty($_POST['quantity']) AND !empty($_POST['product_status']) AND !empty($_POST['warehouse'])){

			$reqExist = $db->prepare('SELECT * FROM ITEM WHERE name = :name');
			$reqExist->execute(array(
				'name' => $_POST['name']
			));
			$nameExist = $reqExist->rowCount();

			if($nameExist == 0){

				if(!(strpos($_POST['name'], ' ') === 0)){

					if($_POST['price'] > 0){

						if($_POST['quantity'] > 0){

						        $_POST['typeOfFood'] = $_POST['typeOfFood'] == 1 ? 1 : 0;
								$_POST['product_status'] = $_POST['product_status'] == 1 ? 1 : 0;
								$reqProduct = $db->prepare('INSERT INTO ITEM(name, price, product_status, dish) VALUES(:name , :price , :product_status, :dish)');
								$reqProduct->execute(array(
									'name' => htmlspecialchars($_POST['name']),
									'price' => htmlspecialchars($_POST['price']),
									'product_status' => htmlspecialchars($_POST['product_status']),
									'dish' => htmlspecialchars($_POST['typeOfFood'])
								));

                                $reqIdItem = $db->prepare('SELECT * FROM ITEM WHERE ITEM.name = :name');
                                $reqIdItem->execute(array(
                                    'name' => htmlspecialchars($_POST['name'])
                                ));
                                $idItemData = $reqIdItem->fetch();

                                $reqIdWarehouse = $db->query('SELECT * FROM WAREHOUSE WHERE WAREHOUSE.address =' . htmlspecialchars($_POST['warehouse']));
                                $idWarehouseData = $reqIdWarehouse->fetch();


                                $reqProduct = $db->prepare('INSERT INTO BELONGIN(idWarehouse, idItem, quantity) VALUES(:idWarehouse, :idItem, :quantity)');
                                $reqProduct->execute(array(
                                    'idWarehouse' => $idWarehouseData['id'],
                                    'idItem' => $idItemData['id'],
                                    'quantity' => htmlspecialchars($_POST['quantity'])
                                ));
								header('Location: shopAdmin.php');
								exit;
						} else {
							$error = 'Stock must be over 0 !';
						}
					} else {
						$error = 'Price must be over 0$ !';
					}
				} else {
					$error = 'The name of your product musn\'t begin with a space !';
				}
			} else {
				$error = 'This product is already in the shop !';
			}
		} else {
			$error = 'Please fill in all the fields !';
		}
	}
?>
<main>
	<div class="row">
        <section class="col-lg-3">
        </section>
        <section class="col-lg-6">
			<div class="row">
			<?php
			echo '<article class="col-lg-12 radius">';
				echo '<form method="post">';
					echo '<label>Name :</label><input name="name" class="form-control">';
					echo '<label>Price :</label><input name="price" class="form-control">';
					echo '<label>Quantity :</label><input name="quantity" class="form-control">';
					echo '<label>Product status :</label><select name="product_status" class="form-control">';
						echo '<option value="">What\'s the status for the product ?</option>';
						echo '<option value="1">Displayed</option>';
						echo '<option value="2">Hidden</option>';
					echo '</select>';
                    echo '<label>Warehouse :</label><select name="warehouse" class="form-control">';
                    echo '<option value="">What\'s the warehouse ?</option>';
                        $reqWarehouse = $db->query('SELECT * FROM WAREHOUSE');
                        while($warehouseData = $reqWarehouse->fetch()){
                            echo '<option value=' . $warehouseData["address"]. '>' . $warehouseData["address"] . '</option>';
                        }
                        $reqWarehouse->closeCursor();
                    echo '</select>';
                    echo '<label>Type of food :</label><select name="typeOfFood" class="form-control">';
                    echo '<option value="">Type of food :</option>';
                        echo '<option value="1">Dish</option>';
                        echo '<option value="2">Ingredient</option>';
                    echo '</select>';
					echo '<button type="submit" name="add" class="btn btn-default btn-sm">Create</button>';
				echo '</form>';
				if(isset($error)){
					echo '<font color="red">'. $error ."</font>";
				}
			echo '</article>';
			?>
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