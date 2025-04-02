<?php

session_start();
//global $hostdb, $usr, $pwd, $PDOoptions;
global $pdo;


require_once('authentication.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST['id'])) {
        die("Geen gebruiker gevonden.");
    } elseif (($_SESSION['user']['role_id'] === 1)) {
        die("but you are a admin");
    } try {
    $id = $_POST['id'];
    $userId = $_SESSION['user']['id'];

    // $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
    $qry = "DELETE FROM tweets WHERE user_id=?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$id]);

    $qry = "DELETE FROM users WHERE id=?";
    $stmt = $pdo->prepare($qry);
    $stmt->execute([$id]);
    if ($_SESSION['user']['role_id'] == 1) {
        header('Location: home.php');
        exit(); // Stop further script execution
    } elseif ($_SESSION['user']['role_id'] == 2) {
        header('Location: registreren.php');
        exit(); // Stop further script execution
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
} else {
    // Redirect if the request is not POST
    if ($_SESSION['user']['role_id'] == 1) {
        header('Location: home.php');
        exit();
    } elseif ($_SESSION['user']['role_id'] == 2) {
        header('Location: registreren.php');
        exit();
    }
}


