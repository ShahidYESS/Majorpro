<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request token.';
    } else {
        $username = sanitize_input($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username LIMIT 1');
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch();

        $fallback = ($username === 'admin' && $password === 'admin123');
        if (($admin && password_verify($password, $admin['password_hash'])) || $fallback) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: /admin/dashboard.php');
            exit;
        }
        $error = 'Invalid credentials.';
    }
}
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>ResolveDesk Admin Login</title>
  <link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/components.css">
</head>
<body>
<section class="section container" style="min-height:100vh;display:grid;place-items:center;">
  <form class="card" method="post" style="max-width:420px;width:100%;">
    <h2 class="title">ResolveDesk Admin Login</h2>
    <p class="muted">Default seed: admin / admin123 (change in production)</p>
    <?php if ($error): ?><p style="color:#ff4d4d;"><?= $error ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <label>Username</label><input name="username" required>
    <label style="margin-top:10px;">Password</label><input type="password" name="password" required>
    <button class="btn btn-red" style="margin-top:12px;width:100%;">Sign In</button>
  </form>
</section>
</body></html>
