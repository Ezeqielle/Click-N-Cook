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
        $listOfErrors[] = "Le prenom doit faire entre 2 et 50 caracteres";
    }

    if (strlen($pwd) < 8
        || strlen($pwd) > 25
        || !preg_match("#[a-z]#", $pwd)
        || !preg_match("#[0-9]#", $pwd)
        || !preg_match("#[A-Z]#", $pwd)){
        $error = true;
        $listOfErrors[] = "Le mot de passe doit faire entre 8 et 25 caracteres avec des majuscules, des minuscules et des chiffres";
    }

    if ($pwd != $pwdConfirm){
        $error = true;
        $listOfErrors[] = "Le mot de passe de confirmation ne correspond pas";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = true;
        $listOfErrors[] = "L'email n'est pas valide";
    }elseif (!$error){
        $pdo = connectDB();
        $query = "SELECT id FROM franchisee WHERE email = :email";
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute([":email"=>$email]);
        $result = $queryPrepared->fetch();
        if (!empty($result)){
            $error = true;
            $listOfErrors[] = "L'email existe deja";
        }
    }

    if ($error){
        unset($_POST["inputpassword"]);
        unset($_POST["confirmPassword"]);

        $_SESSION["errors"] = $listOfErrors;
        $_SESSION["errorsInput"] = $_POST;

        header("Location: ../../src/views/BO/franchisee_folder/view_register.php");

    }else{
        $query = "INSERT INTO franchisee
        (lastName, firstName, email, password)
        VALUES
        (:lastName, :firstName, :email, :password)
        WHERE
        driverSLicenceRefenrence = :driverLicence";

        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->bindParam(':fistName',$firstName);
        $queryPrepared->bindParam(':lastName',$lastName);
        $queryPrepared->bindParam(':email',$email);
        $queryPrepared->bindParam(':password',$pwd);
        $queryPrepared->execute();
    }

    header('Location: ../../src/views/BO/franchisee_folder/view_register.php');


}else{
    die("Tentative de Hack ...");
}