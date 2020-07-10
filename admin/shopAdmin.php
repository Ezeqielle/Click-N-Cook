<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1){

	require('headerAdmin.php');

	if(isset($_POST['delete'])){
        $reqQuantityProduct = $db->prepare('DELETE FROM BELONGIN WHERE idItem = :id');
        $reqQuantityProduct->execute(array(
            'id' => $_POST['productId']
        ));

		$reqProduct = $db->prepare('DELETE FROM ITEM WHERE id = :id');
		$reqProduct->execute(array(
			'id' => $_POST['productId']
		));
	}
?>
<main>
	<div class="row">
        <section class="col-lg-3">
        </section>
		<section class="col-lg-6">
			<div class="row">
				<article class="col-lg-12 radius">
					<input type="text" name="search" class="form-control search" id="searchShopAdmin" placeholder="<?php echo TXT_EXTENSIONS_SEARCH; ?>">
					<a role="button" href="shopAddProduct.php" class="btn btn-default btn-sm">
						<?php echo TXT_EXTENSIONS_ADD; ?>
					</a>
                    <a role="button" href="adminSalesHistory.php" class="btn btn-default btn-sm">
                        <?php echo TXT_EXTENSIONS_SALE; ?>
                    </a>
				</article>
			</div>
			<?php

			$reqProduct = $db->query('SELECT * FROM ITEM');

			echo '<div class="row" id="result">';
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
                        echo $quantityData['quantity'] . TXT_EXTENSIONS_LEFT . '<br>';
                        echo $productData['price'] . TXT_CLIENT_MONNEY . '<br>';
                        echo $warehouseData['address'] . '<br>';
                        echo '<input type="hidden" name="productId" value="' . htmlspecialchars($productData['id']) . '">';
                        echo '<button type="submit" name="delete" class="btn btn-default btn-sm">' . TXT_EXTENSIONS_DEL . '</button> ';
                        echo '<a class="btn btn-default btn-sm" href="shopModifyProduct.php?id=' . $productData['id'] . '">' . TXT_EXTENSIONS_MOD . '</a>';
                    echo '</form>';
                echo '</article>';
			}
			echo '</div>';
			?>
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