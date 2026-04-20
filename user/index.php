<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/helper.php';
$title = 'Submit Support Ticket';
$departments = ['IT', 'HR', 'Network', 'Finance', 'Operations'];
$issueTypes = ['Software', 'Hardware', 'Access', 'Email', 'Other'];
$priorities = ['Low', 'Medium', 'High'];
require_once __DIR__ . '/../includes/header.php';
?>

<section class="public-hero">
    <div>
        <h1>Submit a Support Ticket</h1>
        <p class="muted">Use this portal to report issues and receive status updates from our support team.</p>
    </div>
    <div class="hero-chip">Internal Support Portal</div>
</section>

<section class="card public-form-card">
    <h2>Support Request Form</h2>
    <form action="/ticket-system/user/submit_ticket.php" method="post" class="grid-form" novalidate>
        <?= csrfInput() ?>
        <label>Full Name
            <input type="text" name="name" value="<?= e(old('name')) ?>" maxlength="100" required>
        </label>
        <label>Email Address
            <input type="email" name="email" value="<?= e(old('email')) ?>" maxlength="150" required>
        </label>
        <label>Department / Category
            <select name="department" required>
                <option value="">Select department</option>
                <?php foreach ($departments as $department): ?>
                    <option value="<?= e($department) ?>" <?= old('department') === $department ? 'selected' : '' ?>>
                        <?= e($department) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Issue Type
            <select name="issue_type" required>
                <option value="">Select issue type</option>
                <?php foreach ($issueTypes as $issueType): ?>
                    <option value="<?= e($issueType) ?>" <?= old('issue_type') === $issueType ? 'selected' : '' ?>>
                        <?= e($issueType) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Priority
            <select name="priority" required>
                <?php foreach ($priorities as $priority): ?>
                    <option value="<?= e($priority) ?>" <?= old('priority', 'Medium') === $priority ? 'selected' : '' ?>>
                        <?= e($priority) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="full-width">Description
            <textarea name="description" rows="5" maxlength="1000" required><?= e(old('description')) ?></textarea>
        </label>
        <div class="full-width actions">
            <button type="submit" class="btn btn-primary">Submit Ticket</button>
            <button type="reset" class="btn btn-ghost">Clear Form</button>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
