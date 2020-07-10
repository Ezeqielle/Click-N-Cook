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

$header = "Mime-Version: 1.0\r\n";
$header .='From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
$header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
$header .= 'Content-Transfer-Encoding: 8bit';
$email = $_SESSION['email'];
$name = $_SESSION['name'];

$key = md5(microtime(TRUE) * 100000);
$verif = $db->prepare('	UPDATE CLIENT SET verif_key = ? WHERE email = ? ');
$verif->execute(array($key, $email));

$objet = TXT_MAIL_RE;
$message = "
<html>
<head>
   <title>" . TXT_MAIL_TYCLICK . "</title>
</head>
<body>
	<div align='center'>
		<h1>" . TXT_MAIL_WELCOME . "</h1>
	</div>
   <p style='text-indent: 15px; font-weight:bold;'>" . TXT_MAIL_HELLO . $name . " !</p></br>
   <p>" . TXT_MAIL_PLZ . "</p></br>
   <a href='http://www.clickncook.ovh/sendMail/activation.php?log=" . urlencode($email) . "&key=" . urlencode($key) . "'>" . TXT_MAIL_CLICK . "</a></br>
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
                        <a class="nav clickNCook navbar-brand" href="../login/index.php" title="<?php echo TXT_INDEX_LOGIN; ?>">Click'N Cook</a>
                    </div>
                </div>
            </nav>
        </header>
    </div>
    <main>
        <div class="container height">
            <div class="card card-container">
                <form class="form-signin" method="post">
                    <p align="center" style="color: red"><?php echo TXT_MAIL_CHECK ; ?></p>
                </form>
            </div>
        </div>
    </main>
<?php
include('footer.php');
?>