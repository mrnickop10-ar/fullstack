<?php
require 'session.php';
require 'db.php';

$error = "";

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("Invalid request");
    }

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || empty($password)) {
        $error = "Invalid email or password";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, password FROM users WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
    <p><?= htmlspecialchars($error) ?></p>
</form>
