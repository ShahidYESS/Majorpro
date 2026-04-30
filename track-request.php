<?php
require_once __DIR__ . '/config/helpers.php';
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk - Track Request</title>
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
      <a href="index.php">Home</a><a href="new-request.php">New Request</a><a href="track-request.php">Track Request</a>
      <?php if ($user): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a><a href="signup.php">Sign Up</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<section class="section container">
  <h1 class="title">ResolveDesk - Track Request</h1>
  <form id="trackForm" class="card" style="margin-top:18px;">
    <label>Ticket ID</label>
    <input name="ticket_id" placeholder="Enter Ticket ID e.g. RSD-2026-ABC123" style="font-family:'Orbitron';">
    <button class="btn btn-red" style="margin-top:10px;" type="submit">Search</button>
  </form>
  <div id="result" style="margin-top:20px;"></div>
</section>

<script>
document.getElementById('trackForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const result = document.getElementById('result');
  result.innerHTML = '<div class="card">Loading...</div>';
  const ticket = e.target.ticket_id.value;
  const res = await fetch('api/track-request.php?ticket_id=' + encodeURIComponent(ticket));
  const json = await res.json();
  if (!json.success) {
    result.innerHTML = '<div class="card red">Ticket not found</div>';
    return;
  }
  const d = json.data;
  result.innerHTML = `
    <div class="card">
      <h3 style="color:var(--accent-gold)">${d.ticket_id}</h3>
      <p><span class="status" style="background:${d.status_color};">${d.status}</span></p>
      <p><strong>Type:</strong> ${d.request_type}</p>
      <p><strong>Product:</strong> ${d.product_category} / ${d.product_model}</p>
      <p><strong>Date:</strong> ${d.created_at}</p>
      <p><strong>Admin Notes:</strong> ${d.admin_notes || '-'}</p>
    </div>`;
});
</script>
</body>
</html>
