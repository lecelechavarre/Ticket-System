<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$filters = [
    'start_date' => sanitizeText($_GET['start_date'] ?? '', 20),
    'end_date' => sanitizeText($_GET['end_date'] ?? '', 20),
    'department' => sanitizeText($_GET['department'] ?? '', 100),
    'issue_type' => sanitizeText($_GET['issue_type'] ?? '', 100),
];

$tickets = getClosedTickets($filters);
$title = 'Closed Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <div class="section-head">
        <h2>Closed Ticket Archive</h2>
        <p class="muted">Review resolved and cancelled tickets with full closure context.</p>
    </div>
    <form method="get" class="grid-form compact">
        <label>Start Date
            <input type="date" name="start_date" value="<?= e($filters['start_date']) ?>">
        </label>
        <label>End Date
            <input type="date" name="end_date" value="<?= e($filters['end_date']) ?>">
        </label>
        <label>Department
            <input type="text" name="department" value="<?= e($filters['department']) ?>" placeholder="IT">
        </label>
        <label>Issue Type
            <input type="text" name="issue_type" value="<?= e($filters['issue_type']) ?>" placeholder="Software">
        </label>
        <div class="actions">
            <button class="btn btn-primary">Apply Filters</button>
            <a class="btn btn-secondary" href="/ticket-system/admin/closed.php">Reset</a>
        </div>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
            <tr><th>Ticket</th><th>User</th><th>Status</th><th>Reason</th><th>Resolution Notes</th><th>Updated</th></tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= e($ticket['ticket_code']) ?></td>
                    <td><?= e($ticket['name']) ?></td>
                    <td><span class="badge status-<?= strtolower($ticket['status']) ?>"><?= e($ticket['status']) ?></span></td>
                    <td><?= e($ticket['cancel_reason'] ?? '-') ?></td>
                    <td><?= $ticket['resolution_notes'] ? nl2br(e($ticket['resolution_notes'])) : '-' ?></td>
                    <td><?= e($ticket['updated_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($tickets === []): ?>
                <tr><td colspan="6" class="muted">No closed tickets found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
