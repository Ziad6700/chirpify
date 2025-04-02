<?php
session_start();

require_once('authentication.php');

if (isset($_GET['logout'])) {
    logout();
    header('Location: new-index.php');
    exit();
}

global $pdo;

try {
    //$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
    $qry = "SELECT tweets.id, tweets.user_id, tweets.content, tweets.created_at, users.name
            FROM tweets
            JOIN users ON tweets.user_id = users.id
            LEFT JOIN likes ON tweets.id = likes.tweet_id
            GROUP BY tweets.id
            ORDER BY users.name DESC";
    $stmt = $pdo->query($qry);
    $tweets = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    $tweets  = [];
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
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 1): ?>
    <header>
        <nav class="game">
            <div>
                <a href="">Profile</a>
                <a href="?logout=1">Logout</a>
            </div>
        </nav>
    </header>
    <main>
        <h1>Home page</h1>
        <p>Welcome admin, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!</p>

        <?php if (!empty($msg)): ?>
            <?php foreach ($msg as $message): ?>
                <div class="error"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php foreach ($tweets as $tweet): ?>
            <div>
                <p><strong><?php echo htmlspecialchars($tweet['name']); ?></strong></p>
                <p><?php echo htmlspecialchars($tweet['content']); ?></p>
                <p><small><?php echo $tweet['created_at']; ?></small></p>

                <?php if ($_SESSION['user']['role_id'] == 1 || ($_SESSION['user']['id'] == $tweet['user_id'] && $_SESSION['user']['role_id'] == 2)): ?>
                    <form method="post" action="admindelete.php">
                        <input type="hidden" name="id" value="<?php echo $tweet['id']; ?>">
                        <button type="submit" name="delete">Verwijderen</button>
                    </form>
                <?php endif; ?>
                <?php if ($_SESSION['user']['role_id'] == 1 || ($_SESSION['user']['id'] == $tweet['user_id'] && $_SESSION['user']['role_id'] == 2)): ?>
                    <form method="post" action="adminaccdelete.php">
                        <input type="hidden" name="id" value="<?php echo $tweet['user_id']; ?>">
                        <button type="submit" name="delete">Verwijder account</button>
                    </form>

                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

<?php else: ?>
    <?php header('Location: registreren.php'); ?>
<?php endif; ?>
</body>
</html>
