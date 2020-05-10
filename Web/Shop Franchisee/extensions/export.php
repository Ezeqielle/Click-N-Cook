<?php
//session_start();
require_once "../bdd/conf.inc.php";

try {
    $db = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(Exception $e) {
	die('Erreur : ' . $e->getMessage());
}
//if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1) {

	$reqProductsData = $db->query('SELECT * FROM ITEM');

	unlink('../admin/exports/productsData.csv');
	$csv = fopen('../admin/exports/productsData.csv', 'a');
	fputs($csv, 'id;name;price;n_mark;stock;product_status' . "\n");

	while($productData = $reqProductsData->fetch()) {
		$product = $productData['id'] . ';' . $productData['name'] . ';' . $productData['price'] . ';' . $productData['n_mark'] . ';' . $productData['stock'] . ';' . $productData['product_status'];
		fputs($csv, $product . "\n");
	}

	fclose($csv);
	header('Location: ../admin/shopAdmin.php');
	exit;

/*} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	header('Location: ../home.php');
	exit;
}*/
?>