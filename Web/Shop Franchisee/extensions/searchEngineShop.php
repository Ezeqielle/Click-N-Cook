<?php
session_start();
require('callDatabase.php');

//on vérifie que les données soit bien recus et on affiche pour la recherche
if(isset($_POST['product'])) {
	$product = (String) trim($_POST['product']);

	$reqProduct = $db->prepare('SELECT name, stock, price FROM PRODUCT WHERE name LIKE ?');
	$reqProduct->execute(array("$product%"));
	$productDatas = $reqProduct->fetchALL();

	//s'il existe des users on les affiche sinon on affiche un message
	if($productDatas != NULL) {
		foreach($productDatas as $productData) {
			
			echo '<article name="products" class="col-lg-4">';
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
	} else {
		?>
		<article class="col-lg-12" style="background-color: white;">
			<div style='font-size: 20px; text-align: center;'>
				<strong>There is no product named as follows</strong>
			</div>
		</article>
		<?php
	}
}
if(isset($_POST['reset'])) {
	$reqProduct = $db->query('SELECT name, stock, price FROM PRODUCT');
	
	while($productData = $reqProduct->fetch()) {
		echo '<article name="products" class="col-lg-4">';
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
}
?>