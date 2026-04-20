<?php
declare(strict_types=1);
require_once __DIR__ . '/../functions/helper.php';
$title       = 'Submit Support Ticket';
$departments = ['IT', 'HR', 'Network', 'Finance', 'Operations'];
$issueTypes  = ['Software', 'Hardware', 'Access', 'Email', 'Other'];
$priorities  = ['Low', 'Medium', 'High'];
require_once __DIR__ . '/../includes/header.php';
?>

<div class="hero">
  <div class="hero-inner">
    <div>
      <h1>Submit a Support Ticket</h1>
      <p>Report an issue and our team will respond with a status update shortly.</p>
    </div>
    <div class="hero-chip">Internal Support Portal</div>
  </div>
</div>

<div class="card">
  <div class="card-hd">
    <h2>Support Request Form</h2>
    <p>Fill in the details below to create a new support ticket.</p>
  </div>

  <form action="/ticket-system/user/submit_ticket.php" method="post" novalidate>
    <?= csrfInput() ?>
    <div class="form-grid">

      <div class="field">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name"
               value="<?= e(old('name')) ?>" maxlength="100" placeholder="Jane Smith" required>
      </div>

      <div class="field">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="<?= e(old('email')) ?>" maxlength="150" placeholder="jane@company.com" required>
      </div>

      <div class="field">
        <label for="department">Department</label>
        <select id="department" name="department" required>
          <option value="">Select department…</option>
          <?php foreach ($departments as $dept): ?>
            <option value="<?= e($dept) ?>" <?= old('department') === $dept ? 'selected' : '' ?>>
              <?= e($dept) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label for="issue_type">Issue Type</label>
        <select id="issue_type" name="issue_type" required>
          <option value="">Select issue type…</option>
          <?php foreach ($issueTypes as $type): ?>
            <option value="<?= e($type) ?>" <?= old('issue_type') === $type ? 'selected' : '' ?>>
              <?= e($type) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label for="priority">Priority Level</label>
        <select id="priority" name="priority" required>
          <?php foreach ($priorities as $p): ?>
            <option value="<?= e($p) ?>" <?= old('priority', 'Medium') === $p ? 'selected' : '' ?>>
              <?= e($p) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field full">
        <label for="description">Issue Description</label>
        <textarea id="description" name="description" rows="5" maxlength="1000"
                  placeholder="Describe the issue you're experiencing in detail…" required><?= e(old('description')) ?></textarea>
      </div>

      <div class="form-actions full">
        <button type="submit" class="btn btn-primary">Submit Ticket</button>
        <button type="reset" class="btn btn-ghost">Clear Form</button>
      </div>

    </div>
  </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>