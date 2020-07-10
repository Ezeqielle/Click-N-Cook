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

$key = md5(microtime(TRUE) * 100000);
$verif = $db->prepare('	UPDATE CLIENT SET verif_key = ? WHERE email = ? ');
$verif->execute(array($key, $email));

$objet = "Confirmation of registration";
$message = "
<html>
<head>
   <title>Thank you for registering on Click'N Cook !</title>
</head>
<body>
	<div align='center'>
		<h1>Click'N Cook welcomes you to our company !</h1>
	</div>
   <p style='text-indent: 15px; font-weight:bold;'>Hello " . $name . " !</p></br>
   <p>Please follow this link to validate your registration :</p></br>
   <a href='http://www.clickncook.ovh/sendMail/activation.php?log=" . urlencode($email) . "&key=" . urlencode($key) . "'>Click here !</a></br>
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
                        <a class="nav clickNCook navbar-brand" href="../extensions/index.php" title="Go back home">Click'N Cook</a>
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
include('../extensions/footer.php');
?>