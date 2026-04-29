<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/helpers.php';
admin_guard();

$status = sanitize_input($_GET['status'] ?? '');
$search = sanitize_input($_GET['search'] ?? '');
$where = 'WHERE 1=1';
$params = [];
if ($status !== '') { $where .= ' AND status = :status'; $params[':status'] = $status; }
if ($search !== '') { $where .= ' AND (full_name LIKE :search OR ticket_id LIKE :search)'; $params[':search'] = "%{$search}%"; }

$counts = [
  'total' => (int) $pdo->query('SELECT COUNT(*) FROM requests')->fetchColumn(),
  'pending' => (int) $pdo->query("SELECT COUNT(*) FROM requests WHERE status='pending'")->fetchColumn(),
  'in_progress' => (int) $pdo->query("SELECT COUNT(*) FROM requests WHERE status='in-progress'")->fetchColumn(),
  'resolved' => (int) $pdo->query("SELECT COUNT(*) FROM requests WHERE status='resolved'")->fetchColumn()
];

$stmt = $pdo->prepare("SELECT ticket_id, full_name, request_type, product_model, status, created_at FROM requests {$where} ORDER BY created_at DESC LIMIT 100");
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en"><head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Dashboard</title>
  <link rel="stylesheet" href="/assets/css/style.css"><link rel="stylesheet" href="/assets/css/components.css">
</head>
<body>
<section class="section container">
  <h1 class="title">DASHBOARD</h1>
  <p class="muted">Welcome, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></p>
  <div class="grid-4">
    <div class="card"><h3><?= $counts['total'] ?></h3><p>Total</p></div>
    <div class="card"><h3><?= $counts['pending'] ?></h3><p>Pending</p></div>
    <div class="card"><h3><?= $counts['in_progress'] ?></h3><p>In Progress</p></div>
    <div class="card"><h3><?= $counts['resolved'] ?></h3><p>Resolved</p></div>
  </div>
  <form class="card" style="margin-top:12px;display:grid;grid-template-columns:1fr 1fr auto;gap:10px;">
    <select name="status">
      <option value="">All Status</option>
      <?php foreach (['pending','in-review','in-progress','resolved','closed'] as $s): ?>
        <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
      <?php endforeach; ?>
    </select>
    <input name="search" value="<?= $search ?>" placeholder="Search name/ticket">
    <button class="btn btn-red">Filter</button>
  </form>
  <div class="table-wrap card" style="margin-top:14px;">
    <table>
      <thead><tr><th>Ticket</th><th>Name</th><th>Type</th><th>Product</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['ticket_id']) ?></td>
          <td><?= htmlspecialchars($r['full_name']) ?></td>
          <td><?= htmlspecialchars($r['request_type']) ?></td>
          <td><?= htmlspecialchars($r['product_model']) ?></td>
          <td><span class="status" style="background:<?= status_color($r['status']) ?>;"><?= htmlspecialchars($r['status']) ?></span></td>
          <td><?= htmlspecialchars($r['created_at']) ?></td>
          <td><a class="btn btn-gold" href="/admin/view-request.php?ticket_id=<?= urlencode($r['ticket_id']) ?>">View</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>
</body></html>
