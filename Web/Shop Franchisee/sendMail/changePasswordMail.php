<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();

$header = "Mime-Version: 1.0\r\n";
$header .='From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
$header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
$header .= 'Content-Transfer-Encoding: 8bit';
$email = $_SESSION['email'];
$name = $_SESSION['name'];

$reqKey = $db->prepare('SELECT verif_key FROM CLIENT WHERE id = :currentId');
$reqKey->execute(array(
	'currentId' => $_SESSION['id']
));
$key = $reqKey->fetch();

$objet = 'Password change';
$message = '
<html>
<head>
   <title>Password change !</title>
</head>
<body>
	<div align="center">
		<h1>Meetzic receive a request to change your password !</h1>
	</div>
	<p style="text-indent: 15px; font-weight:bold;">Hello ' . $name . ' !</p></br>
	<p>If this request doesn\'t come from you, we recommend that you change your password as soon as possible</p></br>
	<a href="http://www.clickncook.ovh/sendMail/activateNewPassword.php?id=' . urldecode($_SESSION['id']) . '&key=' . urldecode($key['verif_key']) . '">Click to change your password !</a></br>
</body>
</html>
';
           
mail($email, $objet, $message, $header);

header('Location: http://meetzic.space/profile.php?change=send');
exit;
?>