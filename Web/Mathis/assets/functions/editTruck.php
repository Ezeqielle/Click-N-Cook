<?php
session_start();
require "../../bdd/connection.php";

if (count($_POST) == 4
    && !empty($_POST["Location"])
    && !empty($_POST["carRegistration"])
    && !empty($_POST["idFranchisee"])
    && !empty($_POST["truck_id"])
    ){

    $truck = $_POST['truck_id'];
    $car = $_POST['carRegistration'];
    $idFra = $_POST['idFranchisee'];
    $loc = $_POST['location'];

    $error = false;
    $listOfError = [];

    if (!is_numeric($idFra)){
        $error = true;
        $listOfError[] = "l'id de franchisé ne peut etre qu'un chiffre";
    }

    if ($error){

        $_SESSION["errors"] = $listOfError;
        $_SESSION["errorsInput"] = $_POST;

        header("Location: ../../src/views/BO/franchisee_folder/view_editTruck.php");
    }else{

        $query = "UPDATE truck SET
        car_registration = '".$car."', idFranchisee = '".$idFra."', location = '".$loc."'";

        $pdo = connectDB();
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute();

        header('Location: ../../src/views/BO/franchisee_folder/view_specTruck.php');

    }

}else{
    die("Tentative de Hack .... !!!");
}