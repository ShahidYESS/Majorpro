<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';
admin_guard();

$ticket = sanitize_input($_GET['ticket_id'] ?? '');
if ($ticket === '') { header('Location: /admin/dashboard.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM requests WHERE ticket_id = :ticket_id LIMIT 1');
$stmt->execute([':ticket_id' => $ticket]);
$row = $stmt->fetch();
if (!$row) { header('Location: /admin/dashboard.php'); exit; }
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>View Request</title>
  <link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/components.css">
</head>
<body>
<section class="section container">
  <a class="btn btn-gold" href="/admin/dashboard.php">Back</a>
  <div class="card" style="margin-top:12px;">
    <h2><?= htmlspecialchars($row['ticket_id']) ?></h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
    <p><strong>Type:</strong> <?= htmlspecialchars($row['request_type']) ?></p>
    <p><strong>Product:</strong> <?= htmlspecialchars($row['product_category']) ?> / <?= htmlspecialchars($row['product_model']) ?></p>
    <p><strong>Subject:</strong> <?= htmlspecialchars($row['subject']) ?></p>
    <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    <form id="updateForm" class="card" style="margin-top:12px;">
      <input type="hidden" name="csrf_token" value="<?= $token ?>">
      <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($row['ticket_id']) ?>">
      <label>Status</label>
      <select name="status"><?php foreach (['pending','in-review','in-progress','resolved','closed'] as $s): ?><option value="<?= $s ?>" <?= $row['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select>
      <label style="margin-top:8px;">Admin Notes</label>
      <textarea name="admin_notes"><?= htmlspecialchars((string) $row['admin_notes']) ?></textarea>
      <button class="btn btn-red" style="margin-top:10px;">Save</button>
    </form>
    <p id="msg" class="muted"></p>
  </div>
</section>
<script>
document.getElementById('updateForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const res = await fetch('/api/admin-update.php', { method: 'POST', body: fd });
  const json = await res.json();
  document.getElementById('msg').textContent = json.message;
});
</script>
</body></html>
