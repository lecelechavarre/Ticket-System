<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$counts = getTicketCounts();
$title = 'Admin Dashboard';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>
<section>
    <div class="section-head">
        <h2>Operational Overview</h2>
        <p class="muted">Real-time count of tickets across workflow stages.</p>
    </div>
    <div class="card-grid">
        <article class="card stat"><h3>Pending</h3><p><?= (int) $counts['Pending'] ?></p><span class="muted">Waiting assignment</span></article>
        <article class="card stat"><h3>Ongoing</h3><p><?= (int) $counts['Ongoing'] ?></p><span class="muted">In progress</span></article>
        <article class="card stat"><h3>Ready</h3><p><?= (int) $counts['Ready'] ?></p><span class="muted">Remote-ready</span></article>
        <article class="card stat"><h3>Resolved</h3><p><?= (int) $counts['Resolved'] ?></p><span class="muted">Closed successfully</span></article>
        <article class="card stat"><h3>Cancelled</h3><p><?= (int) $counts['Cancelled'] ?></p><span class="muted">Duplicate/Expired</span></article>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
