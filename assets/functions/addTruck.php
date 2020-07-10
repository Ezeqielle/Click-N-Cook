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

$pdo = connectDB();

if (count($_POST) == 3
    && !empty($_POST["location"])
    && !empty($_POST["registration"])
    && !empty($_POST["idFranchisee"])){
    

    $location = $_POST["location"];
    $registration = $_POST["registration"];
    $idFranchisee = $_POST["idFranchisee"];

    $error = false;
    $listOfErrors = [];

    $query = "SELECT * FROM TRUCK";

    $queryPrepared = $pdo->prepare($query);
    $queryPrepared->execute();

    while($truckVerify = $queryPrepared->fetch()) {
        if ($registration == $truckVerify['registration']) {
            $error = true;
            $listOfErrors[] = TXT_FUNCTIONS_ERROR1;
        }
    }

    if ($error){
        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["errorsInput"] = $_POST;
        header("Location: /BO/view_newTruck.php");
    } else {


        $query = "INSERT INTO TRUCK (location, idFranchisee, registration) VALUES (:location, :idFranchisee, :registration)";

        $queryPrepared = $pdo->prepare($query);

        $queryPrepared->bindParam(':location', $location);
        $queryPrepared->bindParam(':idFranchisee', $idFranchisee);
        $queryPrepared->bindParam(':registration', $registration);
        $queryPrepared->execute();

        if (isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }

        header("Location: /BO/view_allTruck.php");
    }

}else{
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../login/index.php');
    exit;
}