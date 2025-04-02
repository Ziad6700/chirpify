<?php
session_start();
global $hostdb, $usr, $pwd, $PDOoptions;
require_once('authentication.php');

if (isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = strtolower($email);
    $password = strip_tags($_POST['password']);

    if (empty($email)) {
        $errorMsg = "Enter your email address to login";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Enter a valid email address";
    } elseif (empty($password)) {
        $errorMsg = "Password is required to login";
    } elseif (strlen($password) < 5) {
        $errorMsg = "Password must be at least 5 characters long";
    } else {

        $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
        $qry = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user'] = $row;
                $_SESSION['name'] = $row['name'];
                header("Location: Home.php");
                exit();
            } else {
                $errorMsg = 'Your password or e-mail is incorrect';
            }
        } else {
            $errorMsg = 'Your email or password is incorrect';
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
        <h1>Login</h1>
        <?php if (!empty($errorMsg)) {
                echo '<div class="login_error">' . $errorMsg . '</div>';
        } ?>
        <form method="post" action="#">
            <label for="email"></label>
            <input type="email" name="email" id="email" placeholder="email">

            <label for="password"></label>
            <input type="password" name="password" id="password" placeholder="Password">
            <button type="submit" name="login">Login</button>
        </form>
</div>
</body>
</html>