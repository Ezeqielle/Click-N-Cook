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

$char = '1234567890ABCDEFGHIJKLMOPQRSTUVWXY1234567890abcdefghijklmnopqrstuvwxyz1234567890';
$password = substr(str_shuffle($char), 0, 10);

$password2 = password_hash($password, PASSWORD_DEFAULT);

$newPassword = $db->prepare('UPDATE CLIENT SET pwd = :password WHERE email = :email');
$newPassword->execute(array(
    'password' => $password2,
    'email' => $email
));

$objet = "Password recovery";
$message = "
<html>
<head>
   <title>Password recovery !</title>
</head>
<body>
	<div align='center'>
		<h1>Click'N Cook offers you a new password !</h1>
	</div>
   <p style='text-indent: 15px; font-weight:bold;'>Hello ". $name ." !</p></br>
   <p>The new password is : " . $password . "</p></br>
   <p>We recommend that you change this password as soon as possible</p></br>
   <a href='http://www.clickncook.ovh/login/index.php'>Login !</a></br>
</body>
</html>";

mail($email, $objet, $message, $header);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link href="../bootstrap/docs/dist/css/bootstrap.css" rel="stylesheet">
	    <link rel="stylesheet" type="text/css" href="../css/style.css">
	    <link rel="shortcut icon" href="../images/logo.png ">
        <title>Click'N Cook</title>
	</head>
	<body>
        <div class="container">
            <header>
                <nav class="navbar navbar-inverse navbar-fixed-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a class="nav clickNCook navbar-brand" href="../login/index.php" title="Go back home">Click'N Cook</a>
                        </div>
                    </div>
                </nav>
            </header>
        </div>
		<main>
		    <div class="container height">
		        <div class="card card-container">
		        	<form class="form-signin" method="post">
			            <p align="center" style="color: red">Please Check your mailbox to recover your new password, if you can't find the mail check your spam.</p>
			        </form>
			    </div>
		    </div>
		</main>
<?php
include('../login/indexFooter.php');
?>