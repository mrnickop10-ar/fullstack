<?php
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password) || strlen($password) < 6) {
        $error = "Invalid input";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO users (email, password) VALUES (:email, :password)"
        );
        $stmt->execute([
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        header("Location: login.php");
        exit;
    }
}
?>

<form method="post">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Sign Up</button>
    <p><?= htmlspecialchars($error) ?></p>
</form>
