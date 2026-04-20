<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/helper.php';

if (!empty($_SESSION['admin'])) {
    redirect('/ticket-system/admin/dashboard.php');
}

redirect('/ticket-system/admin/login.php');
