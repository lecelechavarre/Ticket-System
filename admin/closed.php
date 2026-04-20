<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

$filters = [
  'start_date' => sanitizeText($_GET['start_date'] ?? '', 20),
  'end_date'   => sanitizeText($_GET['end_date']   ?? '', 20),
  'department' => sanitizeText($_GET['department']  ?? '', 100),
  'issue_type' => sanitizeText($_GET['issue_type']  ?? '', 100),
];

$tickets       = getClosedTickets($filters);
$title         = 'Closed Tickets';
$isAdminLayout = true;
require_once __DIR__ . '/../includes/header.php';
?>

<div class="filter-bar">
  <form method="get" style="display:grid;grid-template-columns:repeat(4,1fr) auto auto;gap:.75rem;align-items:flex-end">
    <div class="field">
      <label>Start Date</label>
      <input type="date" name="start_date" value="<?= e($filters['start_date']) ?>">
    </div>
    <div class="field">
      <label>End Date</label>
      <input type="date" name="end_date" value="<?= e($filters['end_date']) ?>">
    </div>
    <div class="field">
      <label>Department</label>
      <input type="text" name="department" value="<?= e($filters['department']) ?>" placeholder="e.g. IT">
    </div>
    <div class="field">
      <label>Issue Type</label>
      <input type="text" name="issue_type" value="<?= e($filters['issue_type']) ?>" placeholder="e.g. Software">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="/ticket-system/admin/closed.php" class="btn btn-ghost">Reset</a>
  </form>
</div>

<div class="card">
  <div class="section-head">
    <div>
      <h2>Closed Ticket Archive</h2>
      <p>Review resolved and cancelled tickets with full closure context.</p>
    </div>
    <span class="badge status-resolved"><?= count($tickets) ?> tickets</span>
  </div>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th>Ticket</th>
          <th>Submitter</th>
          <th>Status</th>
          <th>Reason</th>
          <th>Resolution Notes</th>
          <th>Closed</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tickets)): ?>
          <tr><td colspan="6">
            <div class="empty-cell">
              <span class="e-icon">🗃️</span>
              <p>No closed tickets match your filters.</p>
            </div>
          </td></tr>
        <?php else: ?>
          <?php foreach ($tickets as $t): ?>
            <tr>
              <td><div class="td-code"><?= e($t['ticket_code']) ?></div></td>
              <td>
                <div class="td-name"><?= e($t['name']) ?></div>
                <div class="td-sub"><?= e($t['email']) ?></div>
              </td>
              <td><span class="badge status-<?= strtolower($t['status']) ?>"><?= e($t['status']) ?></span></td>
              <td style="font-size:.8rem;color:var(--smoke)"><?= e($t['cancel_reason'] ?? '—') ?></td>
              <td style="max-width:260px;font-size:.8rem;color:var(--mist)">
                <?= $t['resolution_notes'] ? nl2br(e(mb_substr($t['resolution_notes'], 0, 120)) . (mb_strlen($t['resolution_notes']) > 120 ? '…' : '')) : '<span style="color:var(--smoke)">—</span>' ?>
              </td>
              <td style="white-space:nowrap;font-size:.78rem;color:var(--smoke)"><?= e($t['updated_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>