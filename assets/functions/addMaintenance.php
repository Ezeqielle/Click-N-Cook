<?php
session_start();

require "functions.php";
require "../../bdd/connection.php";

include('../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../lang/fr-lang.php');
} else {
    include('../../lang/en-lang.php');
}


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
         $listOfErrors[] = TXT_FUNCTIONS_ERROR7;
     }

     if (!is_numeric($price)){
         $error = true;
         $listOfErrors[] = TXT_FUNCTIONS_ERROR8;
     }

     if (!is_numeric($mileage)){
         $error = true;
         $listOfErrors[] = TXT_FUNCTIONS_ERROR9;
     }

     if ($error){
         $_SESSION["errors"] = $listOfErrors;
         $_SESSION["errorsInput"] = $_POST;
         header("Location: /BO/view_newMaintenance.php");
     }else{
         $pdo = connectDB();
         $query = "INSERT INTO MAINTENANCE 
         (date, description, garageName, age, mileage, price, idTruck)
         VALUES
         (:date, :description, :garageName, :age, :mileage, :price, :idTruck)";

         $queryPrepared = $pdo->prepare($query);

         $queryPrepared->bindParam(':date',$date);
         $queryPrepared->bindParam(':description',$description);
         $queryPrepared->bindParam(':garageName',$garage);
         $queryPrepared->bindParam(':age',$age);
         $queryPrepared->bindParam(':mileage',$mileage);
         $queryPrepared->bindParam(':price',$price);
         $queryPrepared->bindParam(':idTruck',$_SESSION['truck']);
         $queryPrepared->execute();
         if(isset($_SESSION["errors"])) {
             unset($_SESSION["errors"]);
         }

         header("Location: /BO/view_allMaintenance.php?idTruck=" . $_SESSION['truck']);
     }



}else{
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../login/index.php');
    exit;
}