<?php
declare(strict_types=1);
require_once __DIR__ . '/../functions/helper.php';
$title       = $title       ?? 'Ticketing System';
$admin       = $_SESSION['admin'] ?? null;
$isAdminLayout = $isAdminLayout ?? false;
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title) ?> — Ticketing System</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/ticket-system/assets/css/style.css">
</head>
<body>
<?php if ($isAdminLayout && $admin): ?>
<div class="app-shell">
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="brand-mark">
        <span class="dot"></span>
        <span class="brand-name">Ticketing</span>
      </div>
      <div class="brand-sub">Admin Panel</div>
    </div>

    <div class="sidebar-section">Navigation</div>
    <nav class="sidebar-nav">
      <a href="/ticket-system/admin/dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span class="nav-label">Dashboard</span>
      </a>
      <a href="/ticket-system/admin/pending.php" class="<?= $currentPage === 'pending.php' ? 'active' : '' ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
        <span class="nav-label">Pending</span>
      </a>
      <a href="/ticket-system/admin/ongoing.php" class="<?= $currentPage === 'ongoing.php' ? 'active' : '' ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9S3 16.97 3 12 7.03 3 12 3s9 4.03 9 9z"/></svg>
        <span class="nav-label">Ongoing</span>
      </a>
      <a href="/ticket-system/admin/closed.php" class="<?= $currentPage === 'closed.php' ? 'active' : '' ?>">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M5 13l4 4L19 7"/></svg>
        <span class="nav-label">Closed</span>
      </a>
    </nav>

    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="user-avatar"><?= strtoupper(substr($admin['username'], 0, 1)) ?></div>
        <div class="user-name"><?= e($admin['username']) ?></div>
        <div class="user-role">Administrator</div>
      </div>
      <nav class="sidebar-nav" style="margin-top:.75rem;margin-bottom:0">
        <a href="/ticket-system/admin/logout.php" class="nav-danger">
          <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          <span class="nav-label">Logout</span>
        </a>
      </nav>
    </div>
  </aside>

  <main class="app-content">
    <div class="page-header">
      <div>
        <h1><?= e($title) ?></h1>
        <p>Ticketing System Admin</p>
      </div>
    </div>
    <?php $flashes = getFlashes(); if ($flashes): ?>
      <div class="toast-wrap">
        <?php foreach ($flashes as $f): ?>
          <div class="toast toast-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

<?php else: ?>
<div class="page-wrap">
  <header class="topbar">
    <div class="container topbar-inner">
      <a class="brand" href="/ticket-system/user/index.php">Ticketing System</a>
      <nav class="public-nav">
        <a href="/ticket-system/user/index.php"
           class="<?= (strpos($_SERVER['PHP_SELF'] ?? '', '/user/') !== false) ? 'active' : '' ?>">
          Submit Ticket
        </a>
        <a href="/ticket-system/admin/login.php" class="btn-gold <?= $currentPage === 'login.php' ? 'active' : '' ?>">
          Admin Login
        </a>
      </nav>
    </div>
  </header>
  <main class="public-main">
    <div class="container">
    <?php $flashes = getFlashes(); if ($flashes): ?>
      <div class="toast-wrap">
        <?php foreach ($flashes as $f): ?>
          <div class="toast toast-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
<?php endif; ?>