<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$ongoing = getTicketsByStatus('Ongoing');
$ready = getTicketsByStatus('Ready');
$tickets = array_merge($ongoing, $ready);
$title = 'Ongoing Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="card">
    <div class="section-head">
        <h2>Active Ticket Management</h2>
        <p class="muted">Track ongoing issues, prepare remote sessions, resolve, or cancel invalid requests.</p>
    </div>
    <?php if ($tickets === []): ?>
        <p class="muted">No ongoing tickets.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Ticket</th><th>Issue</th><th>Status</th><th>Priority</th><th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?= e($ticket['ticket_code']) ?><br><small><?= e($ticket['name']) ?></small></td>
                        <td><?= e($ticket['description']) ?></td>
                        <td><span class="badge status-<?= strtolower($ticket['status']) ?>"><?= e($ticket['status']) ?></span></td>
                        <td><span class="badge priority-<?= strtolower($ticket['priority']) ?>"><?= e($ticket['priority']) ?></span></td>
                        <td class="action-col">
                            <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                                <?= csrfInput() ?>
                                <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">
                                <input type="hidden" name="action" value="ready">
                                <button class="btn btn-secondary">Ready for Remote</button>
                            </form>
                            <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                                <?= csrfInput() ?>
                                <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">
                                <input type="hidden" name="action" value="resolve">
                                <button type="button" class="btn btn-success js-open-resolve-modal"
                                        data-ticket-id="<?= (int) $ticket['id'] ?>"
                                        data-ticket-code="<?= e($ticket['ticket_code']) ?>">
                                    Resolve
                                </button>
                            </form>
                            <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                                <?= csrfInput() ?>
                                <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">
                                <input type="hidden" name="action" value="duplicate">
                                <button class="btn btn-warning">Mark as Duplicate</button>
                            </form>
                            <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                                <?= csrfInput() ?>
                                <input type="hidden" name="ticket_id" value="<?= (int) $ticket['id'] ?>">
                                <input type="hidden" name="action" value="expired">
                                <button class="btn btn-danger">Mark as Expired</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<div class="modal-backdrop hidden js-resolve-modal">
    <div class="modal-card">
        <h3>Resolve Ticket</h3>
        <p class="muted">Add work description before final resolution.</p>
        <form action="/ticket-system/admin/ticket_action.php" method="post" class="grid-form">
            <?= csrfInput() ?>
            <input type="hidden" name="ticket_id" class="js-resolve-ticket-id" value="">
            <input type="hidden" name="action" value="resolve">
            <label class="full-width">Resolution Notes
                <textarea name="resolution_notes" rows="6" minlength="10" maxlength="5000" required
                          placeholder="Describe the root cause and the performed fix."></textarea>
            </label>
            <div class="actions full-width">
                <button type="submit" class="btn btn-success">Confirm Resolution</button>
                <button type="button" class="btn btn-secondary js-close-resolve-modal">Cancel</button>
            </div>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
