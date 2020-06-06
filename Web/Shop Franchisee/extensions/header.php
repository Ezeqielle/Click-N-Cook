<?php

require_once "../bdd/connection.php";
$db = connectDB();

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="page d'accueil">
		<link href="../bootstrap/docs/dist/css/bootstrap.css" rel="stylesheet">
		<link href="../bootstrap/docs/dist/js/bootstrap.js" rel="stylesheet">
  		<link rel="stylesheet" type="text/css" href="../css/style.css">
  		<link rel="shortcut icon" href="../images/logo.png ">
		<title>Click'N Cook</title>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
		<script src="../js/popup.js" type="text/javascript"></script>
		<script src="../js/searchShop.js" type="text/javascript"></script>
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
							<a class="nav meetZic navbar-brand" href="" title="Go back home">Click'N Cook</a>
				        </div>
				        <div id="navbar" class="collapse navbar-collapse">
				          <ul class="nav navbar-nav">
								<li>
                                    <a <!--href=" $_SESSION['administrator'] == true ? 'shopAdmin.php' : 'shop.php'; ?>-->"
                                    class="glyphicon glyphicon-shopping-cart"
                                    title="Shop"
                                    >
                                    </a>
                                </li>
							</ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li> <a href="" class="glyphicon glyphicon-off"></a> </li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li> <a href="#" title="Change language"> <!-- $_SESSION['lang'] == 'FR' ? 'EN' : 'FR'; ?>--></a> </li>
                            </ul>
				        </div>
			     	</div>
			    </nav>
			</header>