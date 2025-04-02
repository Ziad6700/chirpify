<?php
session_start();
global $pdo;

require_once('authentication.php');

$msg = [];
$users = [];

try {
 //$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
$userId = $_SESSION['user']['id'];
$qry = "SELECT name, email, password FROM users WHERE id = :id";
$stmt = $pdo->prepare($qry);
$stmt->bindParam(':id', $userId, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
$msg[] = "Database error: " . $e->getMessage();
$users = [];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<main>
    <?php if (!empty($msg)): ?>
    <?php foreach ($msg as $message): ?>
            <?= htmlspecialchars($message, ENT_QUOTES) ?>
    <?php endforeach; ?>
    <?php endif; ?>

    <h1>Settings</h1>
    <form action="update.php" method="post">
        <label for="name"></label>
        <input type="text" id="name" name="name" value="<?php echo $users['name'] ?? ''; ?>" placeholder="change your name">

        <label for="email"></label>
        <input type="email" id="email" name="email" value="<?php echo $users['email'] ?? ''; ?>" placeholder="change your email">


        <label for="password"></label>
        <input type="password" id="password" name="password" placeholder="change your password">

        <input type="submit" name="submit">
    </form>
</main>
</body>
</html>