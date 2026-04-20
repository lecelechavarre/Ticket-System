<?php
declare(strict_types=1);
require_once __DIR__ . '/../functions/ticket_functions.php';

if (!empty($_SESSION['admin'])) {
    redirect('/ticket-system/admin/dashboard.php');
}

if (isPost()) {
    if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
        flash('error', 'Invalid session token.');
        redirect('/ticket-system/admin/login.php');
    }
    $username = sanitizeText($_POST['username'] ?? '', 100);
    $password = (string) ($_POST['password'] ?? '');

    if (attemptAdminLogin($username, $password)) {
        flash('success', 'Welcome back.');
        redirect('/ticket-system/admin/dashboard.php');
    }
    flash('error', 'Invalid username or password.');
    redirect('/ticket-system/admin/login.php');
}

$flashes = getFlashes();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Login — Ticketing System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/ticket-system/assets/css/style.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-panel">

    <div class="auth-logo">
      <div class="al-brand">
        <span></span>Ticketing System
      </div>
      <div class="al-sub">Secure Administrator Access</div>
    </div>

    <?php if ($flashes): ?>
      <div class="toast-wrap" style="margin-bottom:1.25rem">
        <?php foreach ($flashes as $f): ?>
          <div class="toast toast-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="auth-card">
      <h1>Sign In</h1>
      <p class="auth-sub">Enter your administrator credentials to continue.</p>

      <form method="post" class="auth-form">
        <?= csrfInput() ?>
        <div class="field">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required maxlength="100" placeholder="admin" autocomplete="username">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required placeholder="••••••••" autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:.25rem">Sign In</button>
      </form>
    </div>

    <p style="text-align:center;margin-top:1.25rem;font-size:.78rem;color:var(--smoke)">
      <a href="/ticket-system/user/index.php" style="color:var(--smoke);text-decoration:none;hover:color:var(--white)">← Back to Support Portal</a>
    </p>
  </div>
</div>
<script src="/ticket-system/assets/js/main.js"></script>
</body>
</html>