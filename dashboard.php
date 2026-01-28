<?php
require 'session.php';
require 'db.php';

// Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

// Not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch user
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = :id");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<h2>Welcome, <?= htmlspecialchars($user['email']) ?></h2>

<form method="post">
    <button type="submit" name="logout">Logout</button>
</form>
