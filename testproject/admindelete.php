<?php
session_start();
//global $hostdb, $usr, $pwd, $PDOoptions;
global $pdo;


require_once('authentication.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "test";
    $userId = $_SESSION['user']['id'];
    $id = $_POST['id'];

//$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
    if ($_SESSION['user']['role_id'] == 1) {
        $qry = "DELETE FROM tweets WHERE id=?";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$id]);
    } else {
        $qry = "DELETE FROM tweets WHERE id=? and user_id=?";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$id, $_SESSION['user']['id']]);
    }
    header('Location: admin.php');
    exit();
}



