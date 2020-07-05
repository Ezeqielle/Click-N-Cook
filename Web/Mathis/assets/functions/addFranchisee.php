<?php
session_start();

require "functions.php";
require "../../bdd/connection.php";

if (count($_POST) == 6
&& !empty($_POST["firstName"])
&& !empty($_POST["lastName"])
&& !empty($_POST["driverLicence"])
&& !empty($_POST["email"])
&& !empty($_POST["inputpassword"])
&& !empty($_POST["confirmPassword"])){

    $firstName = ucwords(strtolower(trim($_POST["firstName"])));
    $lastName = ucwords(strtolower(trim($_POST["lastName"])));
    $driver = $_POST["driverLicence"];
    $email = strtolower(trim($_POST["email"]));
    $pwd = $_POST["inputpassword"];
    $pwdConfirm = $_POST["confirmPassword"];

    $error = false;
    $listOfErrors = [];

    if (strlen($firstName) < 2 || strlen($firstName) > 50){
        $error = true;
        $listOfErrors[] = "The first name must be between 2 and 50 characters long !";
    }

    if (strlen($pwd) < 8
        || strlen($pwd) > 25
        || !preg_match("#[a-z]#", $pwd)
        || !preg_match("#[0-9]#", $pwd)
        || !preg_match("#[A-Z]#", $pwd)){
        $error = true;
        $listOfErrors[] = "The password must be between 8 and 25 characters with upper and lower case letters and numbers !";
    }

    if ($pwd != $pwdConfirm){
        $error = true;
        $listOfErrors[] = "Confirmation password does not match !";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = true;
        $listOfErrors[] = "The email is not valid !";
    }elseif (!$error){
        $pdo = connectDB();
        $query = "SELECT id FROM franchisee WHERE email = :email";
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([":email"=>$email]);
        $result = $queryPrepared->fetch();
        if (!empty($result)){
            $error = true;
            $listOfErrors[] = "Email already exists !";
        }
    }

    if ($error){
        unset($_POST["inputpassword"]);
        unset($_POST["confirmPassword"]);

        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["errorsInput"] = $_POST;

        header("Location: ../../src/views/BO/franchisee_folder/view_register.php");

    }else{
        $query = "UPDATE franchisee SET
        password = :password
        WHERE
        driversLicenceRefenrence = :driverLicence";

        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->bindParam(':driverLicence',$driver);
        $queryPrepared->bindParam(':password',$pwd);
        $queryPrepared->execute();
    }

header('Location: MET LE MAIL');


}else{
    die("Tentative de Hack ...");
}