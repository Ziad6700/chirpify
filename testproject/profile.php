<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.php');
    exit;
}

$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "loginsystem";
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$stmt = $con->prepare('SELECT password, username FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($password, $username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>form login</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

</body>
</html>

