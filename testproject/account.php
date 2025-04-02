<?php

session_start();
//global $hostdb, $usr, $pwd, $PDOoptions;
global $pdo;


require_once('authentication.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "test";
    if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role_id']) || $_SESSION['user']['role_id'] != 1) {
        die("Je mag niet anderen gebruikers te verwijderen.");
    }

    if (empty($_POST['id'])) {
        die("Geen gebruiker gevonden.");
    }

    $id = $_POST['id'];
    $userId = $_SESSION['user']['id'];

    if ($id == $userId) {
        die("nee je bent een admin.");
    }

    // $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
    $qry = "DELETE FROM tweets WHERE user_id=?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$id]);

    $qry = "DELETE FROM users WHERE id=?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$id]);
}

header('Location: home.php');
    exit();


