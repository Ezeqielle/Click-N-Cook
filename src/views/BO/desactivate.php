<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1 AND isset($_GET['idUser'])){
    require "../../../assets/functions/functions.php";
    require "../../../bdd/connection.php";

    $pdo = connectDB();
    $user = $_GET['idUser'];


    $queryUser = $pdo->prepare("SELECT * FROM FRANCHISEE WHERE id = " . $user);
    $queryUser->execute();
    $dataUser = $queryUser->fetch();

    if($dataUser['active'] == 0) {
        $queryUserAdd = $pdo->prepare("UPDATE FRANCHISEE SET active = TRUE WHERE id = " . $user);
        $queryUserAdd->execute();
    } else {
        $queryUserAdd = $pdo->prepare("UPDATE FRANCHISEE SET active = FALSE WHERE id = " . $user);
        $queryUserAdd->execute();
    }

    header('Location: view_allFranchisee.php');
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    exit;
}
?>