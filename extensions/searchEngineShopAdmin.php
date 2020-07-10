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

	$reqProduct = $db->prepare('SELECT * FROM ITEM WHERE name LIKE ?');
	$reqProduct->execute(array("$product%"));
	$productDatas = $reqProduct->fetchALL();

	//s'il existe des users on les affiche sinon on affiche un message
	if($productDatas != NULL) {
		foreach($productDatas as $productData) {
            $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
            $quantityData = $reqQuantityProduct->fetch();

            $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
            $warehouseData = $reqWarehouse->fetch();

			echo '<article class="col-lg-4 radius" id="products">';
				echo '<form method="post">';
				if($productData['dish'] == 1) {
					    echo '<label>' . TXT_CLIENT_DISHNAME . '</label></br>';
                    } else {
                        echo '<label>' . TXT_EXTENSIONS_ING . '</label></br>';
                    }
                    echo $productData['product_status'] == 0 ? '<font color="red"><strong>' . TXT_EXTENSIONS_HIDDEN . '</strong></font><br>' : '';
					echo $productData['name'] . '<br>';
					echo $quantityData['quantity'] . ' left<br>';
					echo $productData['price'] . TXT_CLIENT_MONNEY .'<br>';
                    echo $warehouseData['address'] . '<br>';
					echo '<input type="hidden" name="productId" value="' . htmlspecialchars($productData['id']) . '">';
  					echo '<button type="submit" name="delete" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button>';
  					echo '   <a class="btn btn-default btn-sm" href="shopModifyProduct.php?id=' . $productData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
  				echo '</form>';
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
    $reqProduct = $db->query('SELECT * FROM ITEM');

    while($productData = $reqProduct->fetch()){
        $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
        $quantityData = $reqQuantityProduct->fetch();

        $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
        $warehouseData = $reqWarehouse->fetch();

		echo '<article class="col-lg-4 radius" id="products">';
			echo '<form method="post">';
				if($productData['dish'] == 1) {
					    echo '<label>' . TXT_CLIENT_DISHNAME . '</label></br>';
                    } else {
                        echo '<label>' . TXT_EXTENSIONS_ING . '</label></br>';
                    }
                echo $productData['product_status'] == 0 ? '<font color="red"><strong>' . TXT_EXTENSIONS_HIDDEN . '</strong></font><br>' : '';
				echo $productData['name'] . '<br>';
				echo $quantityData['quantity'] . ' left<br>';
				echo $productData['price'] . TXT_CLIENT_MONNEY .'<br>';
                echo $warehouseData['address'] . '<br>';
				echo '<input type="hidden" name="productId" value="' . htmlspecialchars($productData['id']) . '">';
					echo '<button type="submit" name="delete" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button>';
					echo '   <a class="btn btn-default btn-sm" href="shopModifyProduct.php?id=' . $productData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
				echo '</form>';
		echo '</article>';
	}
	$reqProduct->closeCursor();
}
?>