<?php

session_start();
require_once "../bdd/connection.php";
$db = connectDB();

$email = $_GET['log'];
$key = $_GET['key'];

$verif = $db->prepare('SELECT verif_key,active FROM CLIENT WHERE email = ?');
if($verif->execute(array($email)) AND $row = $verif->fetch()) {
	$keydb = $row['verif_key'];
	$accountStatus = $row['account_status'];
}

if ($accountStatus == '1') {
	$char = 'Your account is already activate !';
} elseif ($key == $keydb) {
	$char = 'Your account as been activate !';
	$verif = $db->prepare('UPDATE CLIENT SET active = 1 WHERE email = ?');
	$verif->execute(array($email));
	$verif = $db->prepare('UPDATE CLIENT SET verif_key = NULL WHERE email = ?');
	$verif->execute(array($email));
} else {
	$char = 'ERROR !';
}


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
		        		<p align="center"><?php echo $char; ?></p>
		        		<a role="button" href="../extensions/index.php">
						  	<span style="width:100%; margin-right: 50px; "class="btn btn-lg btn-primary btn-block btn-signin Register">Login</span>
						</a>
			        </form>
			    </div>
		    </div>
		</main>
<?php
include('../extensions/footer.php');
?>