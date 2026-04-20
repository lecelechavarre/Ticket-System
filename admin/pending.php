<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$tickets       = getTicketsByStatus('Pending');
$title         = 'Pending Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
  <div class="section-head">
    <div>
      <h2>Pending Queue</h2>
      <p>Newly submitted tickets awaiting assignment.</p>
    </div>
    <span class="badge status-pending"><?= count($tickets) ?> tickets</span>
  </div>

  <?php if (empty($tickets)): ?>
    <div class="empty-cell">
      <span class="e-icon">📭</span>
      <p>No pending tickets right now.</p>
    </div>
  <?php else: ?>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Ticket ID</th>
            <th>Submitter</th>
            <th>Category</th>
            <th>Priority</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($tickets as $t): ?>
            <tr>
              <td>
                <div class="td-code"><?= e($t['ticket_code']) ?></div>
              </td>
              <td>
                <div class="td-name"><?= e($t['name']) ?></div>
                <div class="td-sub"><?= e($t['email']) ?></div>
              </td>
              <td>
                <div><?= e($t['department']) ?></div>
                <div class="td-sub"><?= e($t['issue_type']) ?></div>
              </td>
              <td><span class="badge priority-<?= strtolower($t['priority']) ?>"><?= e($t['priority']) ?></span></td>
              <td style="white-space:nowrap;font-size:.78rem"><?= e($t['created_at']) ?></td>
              <td>
                <form action="/ticket-system/admin/ticket_action.php" method="post" class="inline-form">
                  <?= csrfInput() ?>
                  <input type="hidden" name="ticket_id" value="<?= (int)$t['id'] ?>">
                  <input type="hidden" name="action"    value="ongoing">
                  <button class="btn btn-primary btn-sm">Claim Ticket</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>