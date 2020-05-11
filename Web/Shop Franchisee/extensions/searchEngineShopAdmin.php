<?php
//session_start();
require_once "../bdd/conf.inc.php";
try {
    $db = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(Exception $e) {
	die('Erreur : '.$e->getMessage());
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
            $reqQuantityProduct = $db->query('SELECT quantity FROM BELONGIN WHERE idItem = ' .$productData["id"]);
            $quantityData = $reqQuantityProduct->fetch();

			echo '<article class="col-lg-4">';
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
    $reqProduct = $db->query('SELECT * FROM ITEM');

    while($productData = $reqProduct->fetch()){
        $reqQuantityProduct = $db->query('SELECT quantity FROM BELONGIN WHERE idItem = ' .$productData["id"]);
        $quantityData = $reqQuantityProduct->fetch();

		echo '<article class="col-lg-4">';
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
}
?>