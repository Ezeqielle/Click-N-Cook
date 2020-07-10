<?php

require "../../bdd/connection.php";
require "/assets/functions.php";

$pdo = connectDB();

if(isset($_GET['id'])){
    $sql = "UPDATE FRANCHISEE SET active = 0 WHERE id = " . $_GET['id'];
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':franchisee',$_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
}