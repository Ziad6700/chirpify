<?php
ob_start();
session_start();
$DATABASE_HOST = "localhost";
$DATABASE_USER = "root";
$DATABASE_PASSWORD = "";
$DATABASE_NAME = "loginsystem";

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

//$hashedPassword = password_hash('hopla', PASSWORD_DEFAULT);
//$sql = "INSERT INTO accounts (username, password) VALUES ('wilma', '$hashedPassword')";
//mysqli_query($con, $sql);
//exit($hashedPassword);

$usernameError = ""; // Default empty
$passwordError = ""; // Default empty

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST['username'], $_POST['password']); // See submitted data
}
if ($stmt = $con->prepare('SELECT id, password FROM users WHERE name = ?')) {
    // Bind alleen de username (s = string)
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords

        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: Home.php');
            exit;
        } else {
            // Incorrect password
            $passwordError = "Wrong username or password!";
        }
        } else {
        // Gebruikersnaam niet gevonden
        $usernameError = "Wrong usern!";
    }


    $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>form login</title>
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<div id="class" class="room">
    <h1>Login</h1>
    <form method="post" action="#">
        <label for="username"></label>
        <input type="text" id="username" name="username" placeholder="username" required>

        <label for="password"></label>
        <input type="password" id="password" name="password" placeholder="password" required>
        <input type="submit" value="login">

        <!-- Alleen tonen als er een fout is -->
        <?php if (!empty($usernameError)) { ?>
            <p class="error"><?php echo $usernameError; ?></p>
        <?php } ?>

        <?php if (!empty($passwordError)) { ?>
            <p class="error"><?php echo $passwordError; ?></p>
        <?php } ?>
    </form>
</div>
</body>
</html>