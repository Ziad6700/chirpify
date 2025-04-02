<?php
session_start();
global $hostdb, $usr, $pwd, $PDOoptions;
require_once 'authentication.php';

if(!isset($_SESSION['user'])){
    header('location: new-index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tweet_id = intval($_POST['tweet_id']);
    $type = $_POST['type'];

    if (!in_array($type, ["like", "dislike"])) {
        echo "Invalid action";
    }

    try {
        $pdo = new PDO($hostdb, $usr, $pwd, $PDOoptions);
        $qry = "SELECT id FROM likes WHERE tweet_id = ? AND user_id = ?";
        $stmt = $pdo->prepare($qry);
        $stmt->execute([$tweet_id, $_SESSION['user']['id']]);
        $existing = $stmt->fetch();

        if ($existing) {
            $qry = "UPDATE likes SET TYPE = ? WHERE id = ?";
            $stmt = $pdo->prepare($qry);
            $stmt->execute([$type, $existing['id']]);
        } else {
            $qry = "INSERT INTO likes (tweet_id, user_id, TYPE) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($qry);
            $stmt->execute([$tweet_id, $_SESSION['user']['id'], $type]);
        }

        header("Location: Home.php");
        exit();
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

