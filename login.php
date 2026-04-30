<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

if (user_logged_in()) {
    header('Location: /new-request.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request token.';
    } else {
        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            header('Location: /new-request.php');
            exit;
        }

        $error = 'Invalid email or password.';
    }
}

$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk - Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Orbitron:wght@500;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
</head>
<body>
<header class="navbar">
  <div class="container nav-wrap">
    <div class="logo">ResolveDesk<span>.</span></div>
    <nav class="nav-links">
      <a href="index.php">Home</a><a href="signup.php">Sign Up</a><a href="admin/login.php">Admin</a>
    </nav>
  </div>
</header>
<section class="section container" style="min-height:85vh;display:grid;place-items:center;">
  <form class="card" method="post" style="max-width:460px;width:100%;">
    <h2 class="title">User Login</h2>
    <p class="muted">Access your ResolveDesk support account</p>
    <?php if ($error): ?><p style="color:#ff4d4d;"><?= $error ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <label>Email</label>
    <input type="email" name="email" required>
    <label style="margin-top:10px;">Password</label>
    <input type="password" name="password" required>
    <button class="btn btn-red" style="margin-top:12px;width:100%;">Login</button>
    <p class="muted" style="margin-top:12px;">New user? <a href="signup.php" style="color:var(--accent-gold);">Create account</a></p>
  </form>
</section>
</body>
</html>
