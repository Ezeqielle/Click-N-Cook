<?php

function createToken(){
    $token = md5(uniqid()."jaduqa,?&èhf%58".time());
    $token = substr($token, 0, rand(10,20));
    $token = str_shuffle($token);
    return $token;
}

/* Franchisee functions */
function loginFranchisee($user){
    $token = createToken();
    $_SESSION["token"] = $token;
    $_SESSION["id"] = $user["id"];
    $_SESSION["email"] = $user["email"];

    $query = "UPDATE franchisee SET token = '".$token."' WHERE id = '".$user["id"]."' AND email = '".$user["email"]."'";

    $pdo = connectDB();
    $pdo->query($query);
}

function isConnectedFranchisee(){
    if(!empty($_SESSION["token"]) && !empty($_SESSION["id"]) && !empty($_SESSION["email"])){
        $query = "SELECT id FROM franchisee WHERE token = '".$_SESSION["token"]."' AND id = '".$_SESSION["id"]."' AND email = '".$_SESSION["email"]."'";
        $pdo = connectDB();
        $query = $pdo->query($query);
        $result = $query->fetch();

        if(!empty($result)){
            $user = ["id"=>$_SESSION["id"], "email"=>$_SESSION["email"]];
            loginFranchisee($user);
            return true;
        }
    }
    return false;
}

function isAdmin(){

}

/* Clients functions */
function loginClients(){

}

function isConnectedClients(){

}
