<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/helper.php';

function requireAdmin(): void
{
    if (empty($_SESSION['admin']) || ($_SESSION['admin']['role'] ?? '') !== 'admin') {
        flash('error', 'Please login as admin.');
        redirect('/ticket-system/admin/login.php');
    }
}
