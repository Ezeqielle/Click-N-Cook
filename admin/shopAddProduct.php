<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1){

	
	include('../lang/lang.php');

	if($_SESSION['lang'] == 'FR') {
	    include('../lang/fr-lang.php');
	} else {
	    include('../lang/en-lang.php');
	}
	
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
							$error = TXT_ADMINS_ERROR1;
						}
					} else {
						$error = TXT_ADMINS_ERROR2;
					}
				} else {
					$error = TXT_EXTENSIONS_ERROR1;
				}
			} else {
				$error = TXT_EXTENSIONS_ERROR2;
			}
		} else {
			$error = TXT_REGISTER_ERROR11;
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
					echo '<label>' . TXT_ADMINS_NAME . '</label><input name="name" class="form-control">';
					echo '<label>' . TXT_ADMINS_PRICE . '</label><input name="price" class="form-control">';
					echo '<label>' . TXT_ADMINS_QTY . '</label><input name="quantity" class="form-control">';
					echo '<label>' . TXT_ADMINS_PRODUCT . '</label><select name="product_status" class="form-control">';
						echo '<option value="">' . TXT_ADMINS_DIS . '</option>';
						echo '<option value="1">' . TXT_ADMINS_HIDE . '</option>';
						echo '<option value="2">' . TXT_ADMINS_WH . '</option>';
					echo '</select>';
                    echo '<label>' . TXT_EXTENSIONS_STATUS . '</label><select name="warehouse" class="form-control">';
                    echo '<option value="">' . TXT_EXTENSIONS_STATUSW . '</option>';
                        $reqWarehouse = $db->query('SELECT * FROM WAREHOUSE');
                        while($warehouseData = $reqWarehouse->fetch()){
                            echo '<option value=' . $warehouseData["address"]. '>' . $warehouseData["address"] . '</option>';
                        }
                        $reqWarehouse->closeCursor();
                    echo '</select>';
                    echo '<label>' . TXT_EXTENSIONS_TYPE . '</label><select name="typeOfFood" class="form-control">';
                    echo '<option value="">' . TXT_EXTENSIONS_TYPE . '</option>';
                        echo '<option value="1">' . TXT_ADMINS_DISH . '</option>';
                        echo '<option value="2">' . TXT_ADMINS_INGREDIENT . '</option>';
                    echo '</select>';
					echo '<button type="submit" name="add" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_CREATE . '</button>';
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