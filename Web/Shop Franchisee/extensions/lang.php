<?php

if(!isset($_SESSION['lang'])){
	$_SESSION['lang'] = 'EN';
}

if(isset($_POST['lang'])){
	$_SESSION['lang'] == 'EN' ? $_SESSION['lang'] = 'FR' : $_SESSION['lang'] = 'EN';
}

?>