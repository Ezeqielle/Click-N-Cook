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

if (count($_POST) == 4
    && !empty($_POST["date"])
    && !empty($_POST["description"])
    && !empty($_POST["price"])){

    $date = $_POST["date"];
    $repaired = $_POST["repaired"];
    $description = ucwords(strtolower(trim($_POST["description"])));
    $price = $_POST["price"];


    $error = false;
    $listOfErrors = [];

    if (!is_numeric($price)){
        $error = true;
        $listOfErrors[] = TXT_FUNCTIONS_ERROR8;
    }

    if ($error){
        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["errorsInput"] = $_POST;
        header("Location: /BO/view_newBreakdown.php");
    }else{
        $pdo = connectDB();
        $query = "INSERT INTO BREAKDOWN 
         (date, description, repaired, price, idTruck)
         VALUES
         (:date, :description, :repaired, :price, :idTruck)";

        $queryPrepared = $pdo->prepare($query);

        $queryPrepared->bindParam(':date',$date);
        $queryPrepared->bindParam(':description',$description);
        $queryPrepared->bindParam(':repaired',$repaired);
        $queryPrepared->bindParam(':price',$price);
        $queryPrepared->bindParam(':idTruck',$_SESSION['truck']);
        $queryPrepared->execute();

        if(isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }

        header("Location: /BO/view_allBreakdown.php?idTruck=" . $_SESSION['truck']);
    }



}else{
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../login/index.php');
    exit;
}