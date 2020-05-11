<?php


require_once "conf.inc.php";

function connectDB()
{
    try {
        $pdo = new PDO(DBDRIVER . ":host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPWD);
    } catch (Exception $e) {
        die("Erreur SQL : " . $e->getMessage());
    }
    return $pdo;
}