<?php

require_once "../bdd/conf.inc.php";

try{
    $db = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch(Exception $e){
	die('Erreur : ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="page d'accueil">
		<link href="bootstrap/docs/dist/css/bootstrap.css" rel="stylesheet">
		<link href="bootstrap/docs/dist/js/bootstrap.js" rel="stylesheet">
  		<link rel="stylesheet" type="text/css" href="../css/style.css">
  		<link rel="shortcut icon" href="images/MeetZic.png ">
		<title>Click'N Cook</title>
		<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<script src="js/popup.js" type="text/javascript"></script>
		<script src="js/chat.js" type="text/javascript"></script>
		<script src="js/searchFriend.js" type="text/javascript"></script>
		<script src="../js/searchShop.js" type="text/javascript"></script>
		<script src="js/searchGroup.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="container">
			<header>
				<nav class="navbar navbar-inverse navbar-fixed-top">
			      	<div class="container">
				        <div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="nav meetZic navbar-brand" href="home.php" title="Go back home">MeetZic</a>
				        </div>
				        <div id="navbar" class="collapse navbar-collapse">
				          <ul class="nav navbar-nav">
								<li> <a href="profile.php" class="glyphicon glyphicon-cog" title="Profil"></a> </li>							
								<li> 
									<a href="<?= $_SESSION['administrator'] == true ? 'admin/shopAdmin.php' : 'shop.php'; ?>" 
									class="glyphicon glyphicon-shopping-cart" 
									title="Shop"
									>
									</a> 
								</li>
								<li> <a href="message.php" class="glyphicon glyphicon-comment" title="Message"></a> </li>
								<li> 
									<a href="<?= $_SESSION['administrator'] == true ? 'admin/groupAdmin.php' : 'group.php'; ?>" 
									class="glyphicon glyphicon-cd" 
									title="Group"
									>
									</a> 
								</li>
								<li> <a href="project.php" class="glyphicon glyphicon-folder-open" title="Project"></a> </li>
								<li> 
									<a href="
									<?= $_SESSION['administrator'] == true ? 'admin/formAdmin.php' : 'form.php'; ?>" 
									class="
									<?= $_SESSION['administrator'] == true ? 'glyphicon glyphicon-envelope' : 'glyphicon glyphicon-question-sign'; ?>" 
									title="
									<?= $_SESSION['administrator'] == true ? 'Forms' : 'Help'; ?>" 
									>
									</a> 
								</li>
								<li> 
									<a href="
									<?= $_SESSION['administrator'] == true ? '/admin/searchUserAdmin.php' : 'searchUser.php'; ?>" 
									class="glyphicon glyphicon-search" 
									title="Search" 
									>
									</a> 
								</li>
							</ul>
					        <ul class="nav navbar-nav navbar-right">
								<li> <a class="glyphicon glyphicon-off" href="disconnect.php"></a> </li>
							</ul>
							<ul class="nav navbar-nav navbar-right">
					        	<li> <a href="#"  title="Change language"><?= $_SESSION['lang'] == 'FR' ? 'EN' : 'FR'; ?></a> </li>
					        </ul>
					        <ul class="nav navbar-nav navbar-right">
					        	<li> <a class="glyphicon glyphicon-user" href="friends.php" title="Add friends"></a> </li>
					        </ul>
					        <?php
					        if($_SESSION['administrator'] == true){
					        ?>
					        <ul class="nav navbar-nav navbar-right">
					        	<li> <a href="admin/userManagement.php" class="glyphicon glyphicon-wrench" title="Manage users" ></a> </li>
					        </ul>
					        <?php
					    	}
					        ?>
				        </div>
			     	</div>
			    </nav>
			</header>