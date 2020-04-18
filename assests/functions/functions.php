<?php

function createToken(){
    $token = md5(uniqid()."jaduqa,?&èhf%58".time());
    $token = substr($token, 0, rand(10,20));
    $token = str_shuffle($token);
    return $token;
}

function login($user){
    $token = createToken();
    $_SESSION["token"] = $token;
    $_SESSION["id"] = $user["id"];
    $_SESSION["email"] = $user["email"];

    $query = "UPDATE franchisee SET token = '".$token."' WHERE id = '".$user["id"]."' AND email = '".$user["email"]."'";

    $pdo = connectDB();
    $pdo->query($query);
}

function isConnected(){
    if(!empty($_SESSION["token"]) && !empty($_SESSION["id"]) && !empty($_SESSION["email"])){
        $query = "SELECT id FROM franchisee WHERE token = '".$_SESSION["token"]."' AND id = '".$_SESSION["id"]."' AND email = '".$_SESSION["email"]."'";
        $pdo = connectDB();
        $query = $pdo->query($query);
        $result = $query->fetch();

        if(!empty($result)){
            $user = ["id"=>$_SESSION["id"], "email"=>$_SESSION["email"]];
            login($user);
            return true;
        }
    }
    return false;
}
