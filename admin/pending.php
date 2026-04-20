<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$tickets = getTicketsByStatus('Pending');
$title = 'Pending Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <div class="section-head">
        <h2>Pending Queue</h2>
        <p class="muted">Newly submitted tickets awaiting ownership.</p>
    </div>
    <?php if ($tickets === []): ?>
        <p class="muted">No pending tickets.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Ticket</th><th>User</th><th>Category</th><th>Priority</th><th>Created</th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= e($ticket['ticket_code']) ?></td>
                        <td><?= e($ticket['name']) ?><br><small><?= e($ticket['email']) ?></small></td>
                        <td><?= e($ticket['department']) ?> / <?= e($ticket['issue_type']) ?></td>
                        <td><span class="badge priority-<?= strtolower($ticket['priority']) ?>"><?= e($ticket['priority']) ?></span></td>
                        <td><?= e($ticket['created_at']) ?></td>
                        <td>
                            <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                                <?= csrfInput() ?>
                                <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">
                                <input type="hidden" name="action" value="ongoing">
                                <button class="btn btn-primary">Get Ticket</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
