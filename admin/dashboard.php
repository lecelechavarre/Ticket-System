<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$counts        = getTicketCounts();
$title         = 'Dashboard';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="stats-grid">
  <div class="stat-card s-pending">
    <div class="stat-label">Pending</div>
    <div class="stat-value"><?= (int)$counts['Pending'] ?></div>
    <div class="stat-sub">Awaiting assignment</div>
  </div>
  <div class="stat-card s-ongoing">
    <div class="stat-label">Ongoing</div>
    <div class="stat-value"><?= (int)$counts['Ongoing'] ?></div>
    <div class="stat-sub">In progress</div>
  </div>
  <div class="stat-card s-ready">
    <div class="stat-label">Ready</div>
    <div class="stat-value"><?= (int)$counts['Ready'] ?></div>
    <div class="stat-sub">Remote-ready</div>
  </div>
  <div class="stat-card s-resolved">
    <div class="stat-label">Resolved</div>
    <div class="stat-value"><?= (int)$counts['Resolved'] ?></div>
    <div class="stat-sub">Closed successfully</div>
  </div>
  <div class="stat-card s-cancelled">
    <div class="stat-label">Cancelled</div>
    <div class="stat-value"><?= (int)$counts['Cancelled'] ?></div>
    <div class="stat-sub">Duplicate or expired</div>
  </div>
</div>

<div class="card">
  <div class="card-hd">
    <h2>Quick Actions</h2>
    <p>Navigate to the most common tasks.</p>
  </div>
  <div style="display:flex;gap:.75rem;flex-wrap:wrap">
    <a href="/ticket-system/admin/pending.php" class="btn btn-primary">View Pending Queue</a>
    <a href="/ticket-system/admin/ongoing.php" class="btn btn-secondary">Manage Ongoing</a>
    <a href="/ticket-system/admin/closed.php" class="btn btn-ghost">Browse Closed Tickets</a>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>