<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/ticket_functions.php';

if (!empty($_SESSION['admin'])) {
    redirect('/ticket-system/admin/dashboard.php');
}

if (isPost()) {
    if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
        flash('error', 'Invalid session token.');
        redirect('/ticket-system/admin/login.php');
    }

    $username = sanitizeText($_POST['username'] ?? '', 100);
    $password = (string) ($_POST['password'] ?? '');

    if (attemptAdminLogin($username, $password)) {
        flash('success', 'Welcome back.');
        redirect('/ticket-system/admin/dashboard.php');
    }

    flash('error', 'Invalid username or password.');
    redirect('/ticket-system/admin/login.php');
}

$title = 'Admin Login';
$isAdminLayout = false;
require_once __DIR__ . '/../includes/header.php';
?>
<section class="card auth-card">
    <h1>Admin Sign In</h1>
    <p class="muted">Secure access for ticketing administrators only.</p>
    <form method="post" class="auth-form">
        <?= csrfInput() ?>
        <label>Username
            <input type="text" name="username" required maxlength="100">
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn btn-primary">Sign In</button>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
