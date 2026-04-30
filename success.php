<?php
require_once __DIR__ . '/config/helpers.php';
$ticket = htmlspecialchars($_GET['ticket_id'] ?? 'N/A', ENT_QUOTES, 'UTF-8');
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk - Request Submitted</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
</head>
<body>
<header class="navbar">
  <div class="container nav-wrap">
    <div class="logo">ResolveDesk<span>.</span></div>
    <nav class="nav-links">
      <a href="index.php">Home</a><a href="new-request.php">New Request</a><a href="track-request.php">Track Request</a>
      <?php if ($user): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a><a href="signup.php">Sign Up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<section class="section container" style="min-height:100vh;display:grid;place-items:center;">
  <div class="card" style="text-align:center;max-width:640px;width:100%;">
    <div style="font-size:3rem;color:var(--accent-gold);">✓</div>
    <h1 class="glitch" style="font-size:2rem;">ResolveDesk Request Submitted!</h1>
    <p class="card" style="border-color:var(--primary-red);font-family:'Orbitron';">Your Ticket: <?= $ticket ?></p>
    <p class="muted">Save this ID to track your request.</p>
    <p style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
      <a class="btn btn-gold" href="track-request.php">Track My Request</a>
      <a class="btn btn-red" href="new-request.php">Submit Another</a>
    </p>
  </div>
</section>
</body>
</html>
