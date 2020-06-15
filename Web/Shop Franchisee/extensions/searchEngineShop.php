<?php
//session_start();
require_once "../bdd/connection.php";
$db = connectDB();

//on vérifie que les données soit bien recus et on affiche pour la recherche
if(isset($_POST['product'])) {
	$product = (String) trim($_POST['product']);

    $reqProduct = $db->prepare('SELECT * FROM ITEM WHERE name LIKE ? AND product_status = true AND (SELECT quantity FROM BELONGIN WHERE quantity > 0 AND BELONGIN.idItem = ITEM.id)');
    $reqProduct->execute(array("$product%"));
	$productDatas = $reqProduct->fetchALL();


	if($productDatas != NULL) {
		foreach($productDatas as $productData) {
            $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
            $quantityData = $reqQuantityProduct->fetch();

            $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
            $warehouseData = $reqWarehouse->fetch();
			
			echo '<article name="products" class="col-lg-4 radius">';
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
	} else {
		?>
		<article class="col-lg-12 radius" style="background-color: white;">
			<div style='font-size: 20px; text-align: center;'>
				<strong>There is no product named as follows</strong>
			</div>
		</article>
		<?php
	}
}
if(isset($_POST['reset'])) {
    $reqProduct = $db->query('SELECT * FROM ITEM WHERE product_status = true AND (SELECT quantity FROM BELONGIN WHERE quantity > 0 AND BELONGIN.idItem = ITEM.id)');

    while($productData = $reqProduct->fetch()) {
        $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
        $quantityData = $reqQuantityProduct->fetch();

        $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
        $warehouseData = $reqWarehouse->fetch();

		echo '<article name="products" class="col-lg-4 radius">';
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
}
?>