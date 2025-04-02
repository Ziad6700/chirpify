<?php
session_start();

require_once('authentication.php');

if (isset($_GET['logout'])) {
    logout();
    header('Location: new-index.php');
    exit();
}

global $pdo;

$msg = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = trim($_POST['content']);

    if (empty($content)) {
        $msg[] = 'Tweet cannot be empty';
    } else {
        try {
            //$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
            $qry = "INSERT INTO tweets (user_id, content) VALUES(?,?)";
            $stmt = $pdo->prepare($qry);
            $stmt->execute([$_SESSION['user']['id'], $content]);

            header('Location: home.php');
            exit();
        } catch (PDOException $e) {
            $msg[] = "Database error: " . $e->getMessage();
        }
    }
}

    try {
        //$pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
        $qry = "SELECT tweets.id, tweets.user_id, tweets.content, tweets.created_at, users.name,
                   SUM(CASE WHEN likes.type = 'like' THEN 1 ELSE 0 END) AS likes,
                   SUM(CASE WHEN likes.type = 'dislike' THEN 1 ELSE 0 END) AS dislikes
            FROM tweets
            JOIN users ON tweets.user_id = users.id
            LEFT JOIN likes ON tweets.id = likes.tweet_id
            GROUP BY tweets.id
            ORDER BY tweets.created_at DESC";
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
<?php if (isset($_SESSION['user'])): ?>
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
        <p>Welcome back, <?= htmlspecialchars($_SESSION['name'], ENT_QUOTES) ?>!</p>

        <?php if (!empty($msg)): ?>
            <?php foreach ($msg as $message): ?>
                <div class="error"><?= htmlspecialchars($message, ENT_QUOTES) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <form method="post" action="">
            <label for="tweet"></label>
            <textarea name="content" id="tweet" placeholder="What's happening?" maxlength="280"></textarea>
            <button type="submit">Tweet</button>
        </form>

        <h2>Recent Tweets</h2>
        <?php foreach ($tweets as $tweet): ?>
            <div>
                <p><strong><?php echo htmlspecialchars($tweet['name']); ?></strong></p>
                <p><?php echo htmlspecialchars($tweet['content']); ?></p>
                <p><small><?php echo $tweet['created_at']; ?></small></p>

                <form method="post" action="like_dislike.php">
                    <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                    <input type="hidden" name="type" value="like">
                    <button type="submit">Like</button>
                </form>
                <form method="post" action="like_dislike.php">
                    <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                    <input type="hidden" name="type" value="dislike">
                    <button type="submit">Dislike</button>
                </form>

                <p>Likes: <?php echo $tweet['likes'] ?? 0; ?> | Dislikes: <?php echo $tweet['dislikes'] ?? 0; ?></p>
                <?php if ($_SESSION['user']['role_id'] == 1 || ($_SESSION['user']['id'] == $tweet['user_id'] && $_SESSION['user']['role_id'] == 2)): ?>
                    <form method="post" action="delete.php">
                        <input type="hidden" name="id" value="<?php echo $tweet['id']; ?>">
                        <button type="submit" name="delete">Verwijderen</button>
                    </form>
                    <?php endif; ?>
                <?php if ($_SESSION['user']['role_id'] == 1 || ($_SESSION['user']['id'] == $tweet['user_id'] && $_SESSION['user']['role_id'] == 2)): ?>
                    <form method="post" action="account.php">
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