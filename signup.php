<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/helpers.php';

if (user_logged_in()) {
    header('Location: /new-request.php');
    exit;
}

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request token.';
    } else {
        $fullName = sanitize_input($_POST['full_name'] ?? '');
        $email = sanitize_input($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($fullName === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please provide valid details.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $exists = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $exists->execute([':email' => $email]);
            if ($exists->fetch()) {
                $error = 'An account with this email already exists.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (:full_name, :email, :password_hash)');
                $insert->execute([
                    ':full_name' => $fullName,
                    ':email' => $email,
                    ':password_hash' => $hash
                ]);
                $success = 'Account created successfully. You can now login.';
            }
        }
    }
}

$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk - Sign Up</title>
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
      <a href="index.php">Home</a><a href="login.php">Login</a><a href="admin/login.php">Admin</a>
    </nav>
  </div>
</header>
<section class="section container" style="min-height:85vh;display:grid;place-items:center;">
  <form class="card" method="post" style="max-width:460px;width:100%;">
    <h2 class="title">Create Account</h2>
    <p class="muted">Sign up to submit and manage requests faster</p>
    <?php if ($error): ?><p style="color:#ff4d4d;"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:#34c759;"><?= $success ?></p><?php endif; ?>
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <label>Full Name</label>
    <input name="full_name" required>
    <label style="margin-top:10px;">Email</label>
    <input type="email" name="email" required>
    <label style="margin-top:10px;">Password</label>
    <input type="password" name="password" required>
    <label style="margin-top:10px;">Confirm Password</label>
    <input type="password" name="confirm_password" required>
    <button class="btn btn-red" style="margin-top:12px;width:100%;">Sign Up</button>
    <p class="muted" style="margin-top:12px;">Already have an account? <a href="login.php" style="color:var(--accent-gold);">Login here</a></p>
  </form>
</section>
</body>
</html>
