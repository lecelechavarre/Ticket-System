<?php
declare(strict_types=1);

require_once __DIR__ . '/../functions/ticket_functions.php';

if (!isPost()) {
    redirect('/ticket-system/user/index.php');
}

if (!validateCsrfToken($_POST['csrf_token'] ?? null)) {
    flash('error', 'Invalid form token. Please try again.');
    redirect('/ticket-system/user/index.php');
}

setOldInput($_POST);
[$errors, $clean] = validateTicketInput($_POST);

if ($errors !== []) {
    flash('error', implode(' ', array_values($errors)));
    redirect('/ticket-system/user/index.php');
}

$ticketCode = createTicket($clean);
if (!$ticketCode) {
    flash('error', 'Unable to submit ticket right now. Please try again.');
    redirect('/ticket-system/user/index.php');
}

clearOldInput();
flash('success', "Ticket submitted successfully. Your Ticket ID: {$ticketCode}");
redirect('/ticket-system/user/index.php');
