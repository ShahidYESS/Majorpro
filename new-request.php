<?php
require_once __DIR__ . '/config/helpers.php';
$token = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk - New Request</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Orbitron:wght@500;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
<div class="container section">
  <h1 class="title">ResolveDesk - New Request</h1>
  <form id="requestForm" enctype="multipart/form-data" class="card" style="margin-top:20px;">
    <input type="hidden" name="csrf_token" value="<?= $token ?>">
    <input type="hidden" name="request_type" value="repair">
    <input type="hidden" name="product_category" value="laptop">
    <input type="hidden" name="priority" value="medium">

    <div class="progress"><div></div></div>
    <div class="step-dots"><span class="step-dot current">1</span><span class="step-dot">2</span><span class="step-dot">3</span></div>

    <section class="wizard-step active">
      <h3>Request Type & Personal Info</h3>
      <div class="grid-3">
        <div class="card type-card active" data-value="repair">🔧 Repair Request</div>
        <div class="card type-card" data-value="enquiry">💬 Product Enquiry</div>
      </div>
      <div class="grid-3" style="margin-top:14px;">
        <div><label>Full Name</label><input required name="full_name"></div>
        <div><label>Email</label><input required name="email" type="email"></div>
        <div><label>Phone</label><input required name="phone"></div>
      </div>
      <div style="margin-top:10px;"><label>Country</label><select name="country"><option>India</option><option>USA</option><option>UK</option><option>Other</option></select></div>
      <button type="button" data-next class="btn btn-red" style="margin-top:16px;">Next</button>
    </section>

    <section class="wizard-step">
      <h3>Product Details</h3>
      <div class="grid-4">
        <?php foreach (['laptop','gpu','monitor','motherboard','peripherals','phone','desktop','other'] as $cat): ?>
          <div class="card cat-card <?= $cat === 'laptop' ? 'active' : '' ?>" data-value="<?= $cat ?>"><?= ucfirst($cat) ?></div>
        <?php endforeach; ?>
      </div>
      <div class="grid-3" style="margin-top:12px;">
        <div><label>Product Model</label><input required name="product_model"></div>
        <div><label>Serial Number</label><input name="serial_number"></div>
        <div>
          <label>Priority</label>
          <div class="pill-row">
            <span class="pill priority-pill" data-value="low">Low</span>
            <span class="pill priority-pill active" data-value="medium">Medium</span>
            <span class="pill priority-pill" data-value="high">High</span>
          </div>
        </div>
      </div>
      <button type="button" data-prev class="btn btn-gold" style="margin-top:16px;">Back</button>
      <button type="button" data-next class="btn btn-red" style="margin-top:16px;">Next</button>
    </section>

    <section class="wizard-step">
      <h3>Describe Your Issue</h3>
      <div class="grid-3">
        <div style="grid-column: span 2;">
          <label>Subject</label><input required name="subject">
          <label style="margin-top:10px;">Description</label><textarea id="description" required name="description" style="min-height:200px;"></textarea>
          <p class="muted">Characters: <span id="charCount">0</span></p>
          <label>Attachment (image/pdf, max 5MB)</label><input name="attachment" type="file" accept=".png,.jpg,.jpeg,.pdf">
        </div>
        <aside class="card"><h4>Summary</h4><div id="summary" class="muted"></div></aside>
      </div>
      <button type="button" data-prev class="btn btn-gold" style="margin-top:16px;">Back</button>
      <button type="submit" class="btn btn-red" style="margin-top:16px;">Submit Request</button>
    </section>
  </form>
</div>
<div id="loadingOverlay" style="display:none;place-items:center;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:100;"><div class="card">Submitting...</div></div>
<script src="assets/js/form.js"></script>
</body>
</html>
