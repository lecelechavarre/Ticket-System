<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/../mail/mailer.php';

const TICKET_PRIORITIES = ['Low', 'Medium', 'High'];
const TICKET_STATUSES = ['Pending', 'Ongoing', 'Ready', 'Resolved', 'Cancelled'];
const TICKET_CANCEL_REASONS = ['Duplicate', 'Expired'];

function generateTicketCode(): string
{
    return 'TCK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function validateTicketInput(array $input): array
{
    $errors = [];

    $name = sanitizeText($input['name'] ?? '', 100);
    $email = sanitizeText($input['email'] ?? '', 150);
    $department = sanitizeText($input['department'] ?? '', 100);
    $issueType = sanitizeText($input['issue_type'] ?? '', 100);
    $description = sanitizeText($input['description'] ?? '', 1000);
    $priority = sanitizeText($input['priority'] ?? '', 20);

    if ($name === '') {
        $errors['name'] = 'Full name is required.';
    }
    if (!validEmail($email)) {
        $errors['email'] = 'Valid email is required.';
    }
    if ($department === '') {
        $errors['department'] = 'Department is required.';
    }
    if ($issueType === '') {
        $errors['issue_type'] = 'Issue type is required.';
    }
    if (mb_strlen($description) < 10) {
        $errors['description'] = 'Description should be at least 10 characters.';
    }
    if (!in_array($priority, TICKET_PRIORITIES, true)) {
        $errors['priority'] = 'Invalid priority selected.';
    }

    return [$errors, compact('name', 'email', 'department', 'issueType', 'description', 'priority')];
}

function createTicket(array $data): ?string
{
    $pdo = getPDO();
    $ticketCode = generateTicketCode();

    $stmt = $pdo->prepare(
        'INSERT INTO tickets (ticket_code, name, email, department, issue_type, description, priority, status)
         VALUES (:ticket_code, :name, :email, :department, :issue_type, :description, :priority, :status)'
    );

    $ok = $stmt->execute([
        ':ticket_code' => $ticketCode,
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':department' => $data['department'],
        ':issue_type' => $data['issueType'],
        ':description' => $data['description'],
        ':priority' => $data['priority'],
        ':status' => 'Pending',
    ]);

    if (!$ok) {
        return null;
    }

    $ticketId = (int) $pdo->lastInsertId();
    addTicketLog($ticketId, 'Created with Pending status');

    return $ticketCode;
}

function addTicketLog(int $ticketId, string $action): void
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO ticket_logs (ticket_id, action) VALUES (:ticket_id, :action)');
    $stmt->execute([
        ':ticket_id' => $ticketId,
        ':action' => $action,
    ]);
}

function getTicketsByStatus(string $status): array
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE status = :status ORDER BY created_at DESC');
    $stmt->execute([':status' => $status]);
    return $stmt->fetchAll();
}

function getClosedTickets(array $filters = []): array
{
    $pdo = getPDO();
    $sql = 'SELECT * FROM tickets WHERE status IN ("Resolved", "Cancelled")';
    $params = [];

    if (!empty($filters['start_date'])) {
        $sql .= ' AND DATE(updated_at) >= :start_date';
        $params[':start_date'] = $filters['start_date'];
    }
    if (!empty($filters['end_date'])) {
        $sql .= ' AND DATE(updated_at) <= :end_date';
        $params[':end_date'] = $filters['end_date'];
    }
    if (!empty($filters['department'])) {
        $sql .= ' AND department = :department';
        $params[':department'] = $filters['department'];
    }
    if (!empty($filters['issue_type'])) {
        $sql .= ' AND issue_type = :issue_type';
        $params[':issue_type'] = $filters['issue_type'];
    }

    $sql .= ' ORDER BY updated_at DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getTicketCounts(): array
{
    $pdo = getPDO();
    $stmt = $pdo->query('SELECT status, COUNT(*) as total FROM tickets GROUP BY status');
    $rows = $stmt->fetchAll();
    $base = ['Pending' => 0, 'Ongoing' => 0, 'Ready' => 0, 'Resolved' => 0, 'Cancelled' => 0];
    foreach ($rows as $row) {
        $base[$row['status']] = (int) $row['total'];
    }
    return $base;
}

function getTicketById(int $id): ?array
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $ticket = $stmt->fetch();
    return $ticket ?: null;
}

function updateTicketStatus(
    int $ticketId,
    string $status,
    ?string $cancelReason = null,
    ?string $resolutionNotes = null
): bool
{
    if (!in_array($status, TICKET_STATUSES, true)) {
        return false;
    }
    if ($status === 'Cancelled' && !in_array((string) $cancelReason, TICKET_CANCEL_REASONS, true)) {
        return false;
    }

    $resolutionNotes = sanitizeText($resolutionNotes, 5000);
    if ($status === 'Resolved' && mb_strlen($resolutionNotes) < 10) {
        return false;
    }

    $ticket = getTicketById($ticketId);
    if (!$ticket) {
        return false;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare(
        'UPDATE tickets
         SET status = :status,
             cancel_reason = :cancel_reason,
             resolution_notes = :resolution_notes,
             updated_at = CURRENT_TIMESTAMP
         WHERE id = :id'
    );

    $ok = $stmt->execute([
        ':status' => $status,
        ':cancel_reason' => $status === 'Cancelled' ? $cancelReason : null,
        ':resolution_notes' => $status === 'Resolved' ? $resolutionNotes : $ticket['resolution_notes'],
        ':id' => $ticketId,
    ]);

    if (!$ok) {
        return false;
    }

    $action = $status === 'Cancelled'
        ? "Cancelled ({$cancelReason})"
        : ($status === 'Resolved' ? "Resolved with notes" : "Moved to {$status}");
    addTicketLog($ticketId, $action);

    if (in_array($status, ['Ongoing', 'Ready', 'Resolved'], true)) {
        [$subject, $html, $text] = buildTicketStatusEmail(
            $ticket['name'],
            $ticket['ticket_code'],
            $status,
            $status === 'Resolved' ? $resolutionNotes : null
        );
        sendTicketEmail($ticket['email'], $subject, $html, $text);
    }

    return true;
}

function attemptAdminLogin(string $username, string $password): bool
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        return false;
    }

    $stored = (string) $user['password'];
    $isValid = password_verify($password, $stored);

    // First-run compatibility: auto-upgrade plain temporary password to hash.
    if (!$isValid && hash_equals($stored, $password)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $update = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
        $update->execute([
            ':password' => $newHash,
            ':id' => (int) $user['id'],
        ]);
        $isValid = true;
    }

    if (!$isValid) {
        return false;
    }

    $_SESSION['admin'] = [
        'id' => (int) $user['id'],
        'username' => $user['username'],
        'role' => $user['role'],
    ];
    session_regenerate_id(true);
    return true;
}
