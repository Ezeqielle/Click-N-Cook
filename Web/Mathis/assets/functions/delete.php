<?php

require "../../bdd/connection.php";
require "../../assets/functions/functions.php";

$pdo = connectDB();

if(isset($_GET['id'])){
    $sql = "DELETE FROM franchisee WHERE id = ".$_GET['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':franchisee',$_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
}