<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && $password == $user['password']) {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Credenziali errate";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body { background: #0F0F0D; color: #F5F0E8; font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-box { background: #1A1A17; padding: 40px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.07); }
        input { display: block; width: 100%; margin: 10px 0; padding: 10px; background: #222; border: 1px solid #333; color: #fff; }
        button { background: #5AA9D9; border: none; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Accesso Admin</h2>
        <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Entra</button>
        </form>
    </div>
</body>
</html>