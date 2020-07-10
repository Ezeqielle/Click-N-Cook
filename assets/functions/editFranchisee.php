<?php
session_start();

if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND ($_SESSION['administrator'] == 0 || $_SESSION['administrator'] == 1)){
    require "../../bdd/connection.php";
    include('../../lang/lang.php');

    if($_SESSION['lang'] == 'FR') {
        include('../../lang/fr-lang.php');
    } else {
        include('../../lang/en-lang.php');
    }


    if (!empty($_POST["firstname"])
    && !empty($_POST["lastname"])
    && !empty($_POST["phone"])
    && !empty($_POST["inputPassword"])
    && !empty($_POST["inputConfirmPassword"]) 
    && !empty($_POST["description"])
    && !empty($_POST["stripeKey"])
    && !empty($_POST["idUser"])){

        $firstname = ucwords(strtolower(trim($_POST['firstname'])));
        $lastname = ucwords(strtolower(trim($_POST['lastname'])));
        $phone = $_POST['phone'];
        $pwd = $_POST['inputPassword'];
        $pwdConfirm = $_POST['inputConfirmPassword'];
        $description = $_POST['description'];
        $stripeKey = $_POST['stripeKey'];
        $user = $_POST['idUser'];

        $error = false;
        $listOfError = [];

        if (strlen($firstname) < 2 || strlen($firstname) > 50){
            $error = true;
            $listOfError[] = TXT_FUNCTIONS_ERROR2;
        }

        if (strlen($lastname) < 2 || strlen($lastname) > 50){
            $error = true;
            $listOfError[] = TXT_FUNCTIONS_ERROR3;
        }

        if (strlen($pwd) < 8
        || strlen($pwd) > 30
        || !preg_match("#[a-z]#", $pwd)
        || !preg_match("#[0-9]#", $pwd)
        || !preg_match("#[A-Z]#", $pwd)){
            $error = true;
            $listOfError[] = TXT_FUNCTIONS_ERROR4;
        }

        if ($pwd != $pwdConfirm){
            $error = true;
            $listOfError[] = TXT_FUNCTIONS_ERROR5;
        }

        if (!is_numeric($phone)){
            $error = true;
            $listOfError[] = TXT_FUNCTIONS_ERROR6;
        }

        if ($error){
            unset($_POST["inputPassword"]);
            unset($_POST["inputConfirmPassword"]);

            $_SESSION["errors"] = $listOfError;
            $_SESSION["errorsInput"] = $_POST;

            header("Location: /BO/franchisee_folder/view_editFranchisee.php");
        }else{
            $pwdTmp = password_hash($pwd, PASSWORD_DEFAULT);

            $pdo = connectDB();


            $queryPrepared = $pdo->prepare('UPDATE FRANCHISEE SET lastName = :lastName, firstName = :firstName, contactNumber = :contactNumber, password = :password, description = :description, stripeKey = :stripeKey WHERE id = :id');
            $queryPrepared->execute(array(
                'lastName' => $lastname,
                'firstName' => $firstname,
                'contactNumber' => $phone,
                'password' => $pwdTmp,
                'description' => $description,
                'stripeKey' => $stripeKey,
                'id' => $user,
            ));

            if(isset($_SESSION["errors"])) {
                unset($_SESSION["errors"]);
            }


            header('Location: /BO/view_specFranchisee.php?idUser=' . $user);

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