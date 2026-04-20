<?php
declare(strict_types=1);
require_once __DIR__ . '/../functions/helper.php';
$title = $title ?? 'Ticketing System';
$admin = $_SESSION['admin'] ?? null;
$isAdminLayout = $isAdminLayout ?? false;
$currentPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="/ticket-system/assets/css/style.css">
</head>
<body>
<?php if ($isAdminLayout && $admin): ?>
<div class="app-shell">
    <aside class="sidebar">
        <div class="sidebar-brand">Ticketing Admin</div>
        <nav class="sidebar-nav">
            <a class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>" href="/ticket-system/admin/dashboard.php">Dashboard</a>
            <a class="<?= $currentPage === 'pending.php' ? 'active' : '' ?>" href="/ticket-system/admin/pending.php">Pending Tickets</a>
            <a class="<?= $currentPage === 'ongoing.php' ? 'active' : '' ?>" href="/ticket-system/admin/ongoing.php">Ongoing Tickets</a>
            <a class="<?= $currentPage === 'closed.php' ? 'active' : '' ?>" href="/ticket-system/admin/closed.php">Closed Tickets</a>
            <a href="/ticket-system/admin/logout.php">Logout</a>
        </nav>
        <div class="sidebar-user">Signed in as <?= e($admin['username']) ?></div>
    </aside>
    <main class="app-content">
        <header class="content-header">
            <h1><?= e($title) ?></h1>
        </header>
        <?php foreach (getFlashes() as $flashMessage): ?>
            <div class="toast toast-<?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div>
        <?php endforeach; ?>
<?php else: ?>
    <header class="topbar">
        <div class="container topbar-inner">
            <a class="brand" href="/ticket-system/user/index.php">Ticketing System</a>
            <nav class="public-nav">
                <a class="<?= $currentPage === 'index.php' && (strpos($_SERVER['PHP_SELF'], '/user/') !== false) ? 'active' : '' ?>" href="/ticket-system/user/index.php">Submit Ticket</a>
                <a class="<?= $currentPage === 'login.php' ? 'active' : '' ?>" href="/ticket-system/admin/login.php">Admin Login</a>
            </nav>
        </div>
    </header>
    <main class="container">
        <?php foreach (getFlashes() as $flashMessage): ?>
            <div class="toast toast-<?= e($flashMessage['type']) ?>"><?= e($flashMessage['message']) ?></div>
        <?php endforeach; ?>
<?php endif; ?>
