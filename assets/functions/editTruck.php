<?php
session_start();

if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1){
    require "../../bdd/connection.php";
    $pdo = connectDB();
    include('../../lang/lang.php');

    if($_SESSION['lang'] == 'FR') {
        include('../../lang/fr-lang.php');
    } else {
        include('../../lang/en-lang.php');
    }

    if (count($_POST) == 4
        && !empty($_POST["location"])
        && !empty($_POST["carRegistration"])
        && !empty($_POST["idFranchisee"])
        && !empty($_POST["truck_id"])
        ){

        $idFra = $_POST['idFranchisee'];
        if($idFra == 0) {
            $idFra = NULL;
        }

        $truck = $_POST['truck_id'];
        $car = $_POST['carRegistration'];

        $loc = $_POST['location'];

        $error = false;
        $listOfError = [];

        $query = "SELECT * FROM TRUCK";

        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute();

        while($truckVerify = $queryPrepared->fetch()) {
            if ($registration == $truckVerify['registration']) {
                $error = true;
                $listOfErrors[] = TXT_REGISTER_ERROR1;
            }
        }

        if ($error == true){

            $_SESSION["errors"] = $listOfError;
            $_SESSION["errorsInput"] = $_POST;

            header("Location: /BO/view_editTruck.php?idTruck=" . $truck);
        } else{
            $queryPrepared = $pdo->prepare('UPDATE TRUCK SET registration = :car, location = :loc, idFranchisee = :idFranchisee WHERE id = :truck');
            $queryPrepared->execute(array(
                'car' => $car,
                'loc' => $loc,
                'idFranchisee' => $idFra,
                'truck' => $truck
            ));

            if(isset($_SESSION["errors"])) {
                unset($_SESSION["errors"]);
            }

            header('Location: /BO/view_specTruck.php?idTruck=' . $truck);

        }

    }else{
        echo '<img src="https://http.cat/401" alt="not found">';
        header('Location: ../../login/index.php');
        exit;
    }
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../login/index.php');
    exit;
}