<?php


require_once "conf.inc.php";

function connectDB()
{
    try{
        $pdo = new PDO( DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME , DBUSER, DBPWD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    } catch(Exception $e){
        die('Erreur : ' . $e->getMessage());
    }
    return $pdo;
}