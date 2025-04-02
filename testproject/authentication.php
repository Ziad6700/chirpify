<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$host = "localhost";
$usr = "root";
$db = "loginsystem";
$pwd = "";
$hostdb = "mysql:host=$host;dbname=$db";

$PDOoptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);

function logout()
{
    session_destroy();
    header("Location: new-index.php");
    exit();
}
