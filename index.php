<?php require_once __DIR__ . '/config/helpers.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ResolveDesk Support Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Orbitron:wght@500;700&family=Rajdhani:wght@500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
  <link rel="stylesheet" href="assets/css/animations.css">
</head>
<body>
  <div class="scroll-progress"></div>
  <header class="navbar">
    <div class="container nav-wrap">
      <div class="logo">ResolveDesk<span>.</span></div>
      <nav class="nav-links">
        <a href="index.php">Home</a><a href="new-request.php">New Request</a><a href="track-request.php">Track Request</a><a href="#faq">FAQ</a>
      </nav>
    </div>
  </header>

  <section class="hero">
    <canvas id="heroParticles"></canvas>
    <div class="hero-content container">
      <p class="badge">OFFICIAL SUPPORT CENTER</p>
      <h1 class="glitch">ResolveDesk Support Portal</h1>
      <p class="muted">Get expert help for your devices with ResolveDesk</p>
      <p style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
        <a class="btn btn-red" href="new-request.php">New Request</a>
        <a class="btn btn-gold" href="track-request.php">Track Request</a>
      </p>
    </div>
  </section>

  <section class="section container">
    <h2 class="title">[ HOW CAN WE HELP? ]</h2>
    <div class="grid-3" style="margin-top:20px;">
      <article class="card red"><h3>Repair Request</h3><p class="muted">Submit a repair job for your device</p><a class="btn btn-red" href="new-request.php">Start Repair</a></article>
      <article class="card blue"><h3>Product Enquiry</h3><p class="muted">Ask about specs, availability, or compatibility</p><a class="btn" style="border-color:var(--sky-blue);color:var(--sky-blue);" href="new-request.php">Ask Now</a></article>
      <article class="card gold featured"><h3>Warranty Check</h3><p class="muted">Verify your product warranty status</p><a class="btn btn-gold" href="track-request.php">Check Now</a></article>
    </div>
  </section>

  <section class="section container">
    <h2 class="title">PRODUCT CATEGORIES</h2>
    <div class="pill-row" style="margin-top:14px;">
      <?php foreach (['Laptops','GPUs','Monitors','Motherboards','Peripherals','Phones','Desktops'] as $cat): ?>
        <span class="pill"><?= $cat ?></span>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section angled">
    <div class="container grid-4">
      <div class="card"><h3 data-counter="50000">0</h3><p class="muted">Tickets Resolved</p></div>
      <div class="card"><h3 data-counter="24">0</h3><p class="muted">Avg Response Time (hr)</p></div>
      <div class="card"><h3 data-counter="98">0</h3><p class="muted">Satisfaction Rate (%)</p></div>
      <div class="card"><h3 data-counter="120">0</h3><p class="muted">Expert Technicians</p></div>
    </div>
  </section>

  <section id="faq" class="section container">
    <h2 class="title">FREQUENT QUESTIONS</h2>
    <?php
      $faqs = [
        'How fast can I get a response?' => 'Most requests are reviewed within 24 hours.',
        'Can I attach proof of purchase?' => 'Yes. Attach PDF or images in the request form.',
        'How do I know my ticket status?' => 'Use Track Request with your ticket ID.',
        'Do you support out-of-warranty devices?' => 'Yes, diagnostic and paid repairs are available.',
        'Can I edit a submitted request?' => 'Contact support with your ticket number for updates.'
      ];
      foreach ($faqs as $q => $a):
    ?>
      <div class="card faq-item" style="margin-top:12px;">
        <button class="faq-q" style="all:unset;display:flex;justify-content:space-between;width:100%;cursor:pointer;font-family:'Rajdhani';font-size:1.1rem;">
          <span><?= $q ?></span><span>+</span>
        </button>
        <div class="faq-a muted" style="max-height:0;overflow:hidden;transition:max-height .25s ease;"><?= $a ?></div>
      </div>
    <?php endforeach; ?>
  </section>

  <footer class="footer">
    <div class="container footer-columns">
      <div><h3 class="logo">ResolveDesk<span>.</span></h3><p class="muted">Fast, reliable technical support by ResolveDesk.</p></div>
      <div><h4>Quick Links</h4><p><a href="new-request.php">New Request</a></p><p><a href="track-request.php">Track Request</a></p></div>
      <div><h4>Contact</h4><p class="muted">support@resolvedesk.local</p><p class="muted">+91 1800-000-RSD</p></div>
    </div>
    <div class="container footer-bottom">© 2026 ResolveDesk Support Portal</div>
  </footer>

  <script src="assets/js/main.js"></script>
  <script src="assets/js/particles.js"></script>
  <script>
    document.querySelectorAll('.faq-item').forEach(item => {
      item.querySelector('.faq-q').addEventListener('click', () => {
        const a = item.querySelector('.faq-a');
        const open = item.classList.toggle('open');
        a.style.maxHeight = open ? a.scrollHeight + 'px' : '0';
      });
    });
  </script>
</body>
</html>
