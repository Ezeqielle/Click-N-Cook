<?php
session_start();

require "functions.php";
require "../../bdd/connection.php";

if (count($_POST) == 6
&& !empty($_POST["date"])
&& !empty($_POST["garageName"])
&& !empty($_POST["age"])
&& !empty($_POST["description"])
&& !empty($_POST["price"])
&& !empty($_POST["mileage"])){

    $date = $_POST["date"];
    $garage = ucwords(strtolower(trim($_POST["garageName"])));
    $age = $_POST["age"];
    $description = ucwords(strtolower(trim($_POST["description"])));
    $price = $_POST["price"];
    $mileage = $_POST["mileage"];

    $error = false;
    $listOfErrors = [];

     if (!is_numeric($age)){
         $error = true;
         $listOfErrors[] = "Age invalide !";
     }

     if (!is_numeric($price)){
         $error = true;
         $listOfErrors[] = "Prix invalide !";
     }

     if (!is_numeric($mileage)){
         $error = true;
         $listOfErrors[] = "Kilometrage invalide !";
     }

     if ($error){
         $_SESSION["errors"] = $listOfErrors;
         $_SESSION["errorsInput"] = $_POST;
         header("Location: ../../src/views/BO/view_new_maintenance.php");
     }else{
         $pdo = connectDB();
         $query = "INSERT INTO users 
         (date, description, garageName, age, mileage, price)
         VALUES
         (:date, :description, :garageName, :age, :mileage, :price)";

         $queryPrepared = $pdo->prepare($query);

         $queryPrepared->bindParam(':date',$date);
         $queryPrepared->bindParam(':description',$description);
         $queryPrepared->bindParam(':garageName',$garage);
         $queryPrepared->bindParam(':age',$age);
         $queryPrepared->bindParam(':mileage',$mileage);
         $queryPrepared->bindParam(':price',$price);
         $queryPrepared->execute();
     }



}else{
    die("tentative de Hack .... !!!!!");
}