<?php
session_start();
require "../../bdd/connection.php";

if (count($_POST) == 6
&& !empty($_POST["firstname"])
&& !empty($_POST["lastname"])
&& !empty($_POST["phone"])
&& !empty($_POST["user_id"])
&& !empty($_POST["inputPassword"])
&& !empty($_POST["inputConfirmPassword"])){

    $user = $_POST['user_id'];
    $firstname = ucwords(strtolower(trim($_POST['firstname'])));
    $lastname = ucwords(strtolower(trim($_POST['lastname'])));
    $phone = $_POST['phone'];
    $pwd = $_POST['inputPassword'];
    $pwdConfirm = $_POST['inputConfirmPassword'];

    $error = false;
    $listOfError = [];

    if (strlen($firstname) < 2 || strlen($firstname) > 50){
        $error = true;
        $listOfError[] = "le prénom doit faire entre 2 et 50 caractères";
    }

    if (strlen($lastname) < 2 || strlen($lastname) > 50){
        $error = true;
        $listOfError[] = "le prénom doit faire entre 2 et 50 caractères";
    }

    if (strlen($pwd) < 8
    || strlen($pwd) > 30
    || !preg_match("#[a-z]#", $pwd)
    || !preg_match("#[0-9]#", $pwd)
    || !preg_match("#[A-Z]#", $pwd)){
        $error = true;
        $listOfError[] = "Le mot de passe doit faire entre 8 et 30 caractères avec des minuscules, des majuscules et des chiffres";
    }

    if ($pwd != $pwdConfirm){
        $error = true;
        $listOfError[] = "Le mot de passe de confirmation ne correspond pas";
    }

    if (!is_numeric($phone)){
        $error = true;
        $listOfError[] = "Le numéro de téléphone ne doit contenir que des chiffres";
    }

    if ($error){
        unset($_POST["inputPassword"]);
        unset($_POST["inputConfirmPassword"]);

        $_SESSION["errors"] = $listOfError;
        $_SESSION["errorsInput"] = $_POST;

        header("Location: ../../src/views/BO/franchisee_folder/view_editFranchisee.php");
    }else{
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $query = "UPDATE franchisee SET
        last_name = '".$lastname."', first_name = '".$firstname."', contact_number = '".$phone."', password = '".$pwd."' WHERE id = '".$user."'";

        $pdo = connectDB();
        $queryPrepared = $pdo->prepare($query);
        $queryPrepared->execute();

        header('Location: ../../src/views/BO/franchisee_folder/view_specFranchisee.php');

    }

}else{
    die("Tentative de Hack .... !!!");
}