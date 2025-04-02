<?php
global $pdo;
session_start();
require_once('authentication.php');

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $email = strtolower(trim($email));
    $name = strip_tags(trim($_POST['name']));
    $password = trim($_POST['password']);
    $repeat_password = trim($_POST['repeat_password']);


    if (empty($name)) {
        $msg = "Name is required";
    } elseif (empty($email)) {
        $msg = "Email is required";
    } elseif (empty($password)) {
        $msg = "Password is required";
    } elseif (empty($repeat_password)) {
        $msg = "Repeat password is required";
    } elseif (empty($_POST["agree"])) {
        $msg = "You must agree to the terms and conditions";
    } elseif ($password !== $repeat_password) {
        $msg = "Passwords do not match";
    } else {
        try {
            $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
            $qry = "SELECT COUNT(*) FROM users WHERE email = ?";
            $stmt = $pdo->prepare($qry);
            $stmt->execute([$email]);

            if ($stmt->fetchColumn() > 0) {
                $msg = "E-mail bestaat al";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $qry = "INSERT INTO users (name, email, password, role_id) VALUES (?, ?, ?, 2)";
                $stmt = $pdo->prepare($qry);
                $stmt->execute([$name, $email, $hashed_password]);

                header("Location: new-index.php");
                exit();
            }
        } catch (PDOException $e) {
            $msg = "Database error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div>
    <h1>registration</h1>
    <?php
    if (!empty($msg)) {
        echo "<p>$msg</p>";
    }
    ?>
    <form method="post" action="#">
        <label for="name"></label>
        <input type="text" id="name" name="name" placeholder="name">

        <label for="password"></label>
        <input type="password" id="password" name="password" placeholder="password">

        <label for="email"></label>
        <input type="email" id="email" name="email" placeholder="email">

        <label for="repeat_password"></label>
        <input type="password" id="repeat_password" name="repeat_password" placeholder="repeat password">

        <label for="agree"></label>
        <input type="checkbox" id="agree" name="agree"> I agree to Terms & Conditions
        <button type="submit">register</button>
    </form>
</div>
</body>
</html>