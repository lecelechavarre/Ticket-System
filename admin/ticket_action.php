<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../functions/ticket_functions.php';
requireAdmin();

if (!isPost() || !validateCsrfToken($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid action request.');
    redirect('/ticket-system/admin/pending.php');
}

$ticketId = (int) ($_POST['ticket_id'] ?? 0);
$action = sanitizeText($_POST['action'] ?? '', 30);
$resolutionNotes = sanitizeText($_POST['resolution_notes'] ?? '', 5000);

$statusMap = [
    'ongoing' => ['status' => 'Ongoing', 'reason' => null, 'redirect' => '/ticket-system/admin/pending.php'],
    'ready' => ['status' => 'Ready', 'reason' => null, 'redirect' => '/ticket-system/admin/ongoing.php'],
    'resolve' => ['status' => 'Resolved', 'reason' => null, 'redirect' => '/ticket-system/admin/ongoing.php'],
    'duplicate' => ['status' => 'Cancelled', 'reason' => 'Duplicate', 'redirect' => '/ticket-system/admin/ongoing.php'],
    'expired' => ['status' => 'Cancelled', 'reason' => 'Expired', 'redirect' => '/ticket-system/admin/ongoing.php'],
    'cancel' => ['status' => 'Cancelled', 'reason' => 'Expired', 'redirect' => '/ticket-system/admin/ongoing.php'],
];

if (!isset($statusMap[$action])) {
    flash('error', 'Unknown action.');
    redirect('/ticket-system/admin/ongoing.php');
}

$target = $statusMap[$action];
if ($target['status'] === 'Resolved' && mb_strlen($resolutionNotes) < 10) {
    flash('error', 'Resolution notes are required (minimum 10 characters).');
    redirect('/ticket-system/admin/ongoing.php');
}

$ok = updateTicketStatus($ticketId, $target['status'], $target['reason'], $resolutionNotes);

if ($ok) {
    flash('success', 'Ticket updated successfully.');
} else {
    flash('error', 'Unable to update the selected ticket.');
}

redirect($target['redirect']);
