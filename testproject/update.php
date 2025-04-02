<?php
session_start();
//global $hostdb, $usr, $pwd, $PDOoptions;
global $pdo;


require_once('authentication.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = strtolower($email);
    $password = strip_tags($_POST['password']);
    $userId = $_SESSION['user']['id'];

    try {

        $pdo->beginTransaction();

        $qry = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$name, $email, $userId]);

        if (!empty($password)){
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $qry = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $pdo->prepare($qry);
            $stmt->execute([$hashed_password, $userId]);
        }

        $pdo->commit();

        header('Location: settings.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        die('database error: ' . $e->getMessage());
    }
}
