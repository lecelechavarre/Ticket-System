<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/helper.php';

unset($_SESSION['admin']);
session_regenerate_id(true);
flash('success', 'Logged out successfully.');
redirect('/ticket-system/admin/login.php');
