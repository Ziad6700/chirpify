<?php
session_start();
require_once 'authentication.php';
global $hostdb, $usr, $pwd, $PDOoptions;

if(!isset($_SESSION['user'])){
    header('location: new-index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = trim($_POST["content"]);

    try {
        $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
        $qry = "INSERT INTO tweets (user_id, content) VALUES(?,?)";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$_SESSION['user']['id'], $content]);

        header('location: home.php');
        exit();
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
