<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$ongoing       = getTicketsByStatus('Ongoing');
$ready         = getTicketsByStatus('Ready');
$tickets       = array_merge($ongoing, $ready);
$title         = 'Ongoing Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
  <div class="section-head">
    <div>
      <h2>Active Ticket Management</h2>
      <p>Track, progress, and close all active tickets.</p>
    </div>
    <span class="badge status-ongoing"><?= count($tickets) ?> active</span>
  </div>

  <?php if (empty($tickets)): ?>
    <div class="empty-cell">
      <span class="e-icon">✅</span>
      <p>No active tickets at this time.</p>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Ticket</th>
            <th>Description</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tickets as $t): ?>
            <tr>
              <td>
                <div class="td-code"><?= e($t['ticket_code']) ?></div>
                <div class="td-sub"><?= e($t['name']) ?></div>
              </td>
              <td>
                <div class="td-desc"><?= e($t['description']) ?></div>
              </td>
              <td><span class="badge status-<?= strtolower($t['status']) ?>"><?= e($t['status']) ?></span></td>
              <td><span class="badge priority-<?= strtolower($t['priority']) ?>"><?= e($t['priority']) ?></span></td>
              <td>
                <div class="action-group">
                  <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                    <?= csrfInput() ?>
                    <input type="hidden" name="ticket_id" value="<?= (int)$t['id'] ?>">
                    <input type="hidden" name="action"    value="ready">
                    <button class="btn btn-secondary btn-sm">Mark Ready</button>
                  </form>

                  <button type="button"
                          class="btn btn-success btn-sm js-open-resolve-modal"
                          data-ticket-id="<?= (int)$t['id'] ?>">
                    Resolve
                  </button>

                  <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                    <?= csrfInput() ?>
                    <input type="hidden" name="ticket_id" value="<?= (int)$t['id'] ?>">
                    <input type="hidden" name="action"    value="duplicate">
                    <button class="btn btn-warning btn-sm">Duplicate</button>
                  </form>

                  <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                    <?= csrfInput() ?>
                    <input type="hidden" name="ticket_id" value="<?= (int)$t['id'] ?>">
                    <input type="hidden" name="action"    value="expired">
                    <button class="btn btn-danger btn-sm">Expired</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<!-- RESOLVE MODAL -->
<div class="modal-backdrop hidden js-resolve-modal">
  <div class="modal-card">
    <h3>Resolve Ticket</h3>
    <p class="sub">Provide resolution notes before marking this ticket as resolved.</p>
    <form action="/ticket-system/admin/ticket_action.php" method="post">
      <?= csrfInput() ?>
      <input type="hidden" name="ticket_id" class="js-resolve-ticket-id" value="">
      <input type="hidden" name="action" value="resolve">
      <div class="field" style="margin-bottom:1.25rem">
        <label for="resolution_notes">Resolution Notes</label>
        <textarea id="resolution_notes" name="resolution_notes" rows="6"
                  minlength="10" maxlength="5000" required
                  placeholder="Describe the root cause and the steps taken to resolve the issue…"></textarea>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-success">Confirm Resolution</button>
        <button type="button" class="btn btn-ghost js-close-resolve-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>