<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();

    include('../lang/lang.php');

    if($_SESSION['lang'] == 'FR') {
        include('../lang/fr-lang.php');
    } else {
        include('../lang/en-lang.php');
    }

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
			
				
			echo '<article name="products" class="col-lg-4 radius">';if($productData['dish'] == 1) {
                        echo '<label>' . TXT_CLIENT_DISHNAME . '</label></br>';
                    } else {
                        echo '<label>' . TXT_EXTENSIONS_ING . '</label></br>';
                    }
                echo $productData['name'] . '<br>';
                echo $quantityData['quantity'] . ' left<br>';
                echo $productData['price'] . TXT_CLIENT_MONNEY .'<br>';
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
				<strong><?php echo TXT_JS_ERROR1; ?></strong>
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

				
		echo '<article name="products" class="col-lg-4 radius">';if($productData['dish'] == 1) {
                        echo '<label>' . TXT_CLIENT_DISHNAME . '</label></br>';
                    } else {
                        echo '<label>' . TXT_EXTENSIONS_ING . '</label></br>';
                    }
			echo $productData['name'] . '<br>';
			echo $quantityData['quantity'] . ' left<br>';
			echo $productData['price'] . TXT_CLIENT_MONNEY .'<br>';
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