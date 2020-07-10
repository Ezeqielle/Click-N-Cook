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
    && !empty($_POST["firstName"])
    && !empty($_POST["lastName"])
    && !empty($_POST["driverLicense"])
    && !empty($_POST["inputEmail"])
    && !empty($_POST["inputPassword"])
    && !empty($_POST["confirmPassword"])){

    $firstName = ucwords(strtolower(trim($_POST["firstName"])));
    $lastName = ucwords(strtolower(trim($_POST["lastName"])));
    $driver = $_POST["driverLicense"];
    $email = strtolower(trim($_POST["inputEmail"]));
    $pwd = $_POST["inputPassword"];
    $pwdConfirm = $_POST["confirmPassword"];

    $error = false;
    $listOfErrors = [];

    if (strlen($firstName) < 2 || strlen($firstName) > 50){
        $error = true;
        $listOfErrors[] = TXT_FUNCTIONS_ERROR2;
    }

    if (strlen($pwd) < 8
        || strlen($pwd) > 25
        || !preg_match("#[a-z]#", $pwd)
        || !preg_match("#[0-9]#", $pwd)
        || !preg_match("#[A-Z]#", $pwd)){
        $error = true;
        $listOfErrors[] = TXT_FUNCTIONS_ERROR4;
    }

    if ($pwd != $pwdConfirm){
        $error = true;
        $listOfErrors[] = TXT_FUNCTIONS_ERROR5;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = true;
        $listOfErrors[] = TXT_FUNCTIONS_ERROR10;
    }

    if ($error){
        unset($_POST["inputpassword"]);
        unset($_POST["confirmPassword"]);

        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["errorsInput"] = $_POST;

        header("Location: /BO/franchisee_folder/view_register.php");

    } else {
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $pdo = connectDB();
        $queryPrepared = $pdo->prepare("UPDATE FRANCHISEE SET password = :password, description = :description WHERE driversLicenceReference = :driverLicence");
        $queryPrepared->execute(array(
            'password' => $pwd,
            'description' => 'Franchise of Click\'N Cook',
            'driverLicence' => $driver
        ));

        if(isset($_SESSION["errors"])) {
            unset($_SESSION["errors"]);
        }




        $_SESSION['email'] = $email;
        $_SESSION['name'] = $lastName;
        header('Location: /sendMail/mailFranchisee.php');
    }


}else{
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../login/index.php');
    exit;
}