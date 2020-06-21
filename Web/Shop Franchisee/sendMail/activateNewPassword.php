<?php
require_once "../bdd/connection.php";
$db = connectDB();

if(isset($_GET['key']) AND isset($_GET['id']) AND !empty($_GET['key']) AND !empty($_GET['id'])) {

	$reqVerifKey = $db->prepare('SELECT verif_key FROM CLIENT WHERE id = :currentId');
	$reqVerifKey->execute(array(
		'currentId' => $_GET['id']
	));
	$verifKey = $reqVerifKey->fetch();
	if($_GET['key'] == $verifKey['verif_key']) {

		$reqTmpPassword = $db->prepare('SELECT tmp_password FROM CLIENT WHERE id = :currentId');
		$reqTmpPassword->execute(array(
			'currentId' => $_GET['id']
		));
		$tmpPassword = $reqTmpPassword->fetch();

		$reqUpdatePassword = $db->prepare('UPDATE CLIENT SET password = :tmpPassword WHERE id = :currentId');
		$reqUpdatePassword->execute(array(
			'tmpPassword' => $tmpPassword['tmp_password'],
			'currentId' => $_GET['id']
		));
		
		$reqUpdateVerifKey = $db->prepare('UPDATE CLIENT SET tmp_password = NULL, verif_key = NULL WHERE id = :currentId');
		$reqUpdateVerifKey->execute(array(
			'currentId' => $_GET['id']
		));

		header('Location: http://www.clickncook.ovh/extensions/index.php?change=success');
	}
}