<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../functions/helper.php';

/**
 * To enable SMTP:
 * 1) Install PHPMailer: composer require phpmailer/phpmailer
 * 2) Configure SMTP_* constants below.
 */
const MAIL_FROM_EMAIL = 'no-reply@example.com';
const MAIL_FROM_NAME = 'Ticketing System';
const SMTP_HOST = '';
const SMTP_PORT = 587;
const SMTP_USERNAME = '';
const SMTP_PASSWORD = '';
const SMTP_SECURE = 'tls';
const MAIL_USE_SMTP = true;

function sendTicketEmail(string $toEmail, string $subject, string $htmlBody, string $plainText): bool
{
    $autoload = __DIR__ . '/../vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    } else {
        logSystem('Mail skipped: PHPMailer not installed.');
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        if (MAIL_USE_SMTP) {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
        }

        $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
        $mail->addAddress($toEmail);
        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $plainText;

        $mail->send();
        return true;
    } catch (Exception $e) {
        logSystem('Mail error: ' . $e->getMessage());
        return false;
    }
}

function buildTicketStatusEmail(string $name, string $ticketCode, string $status, ?string $resolutionNotes = null): array
{
    $subject = "Ticket {$ticketCode} status update: {$status}";
    $message = "Hello {$name}, your ticket ({$ticketCode}) is now marked as {$status}.";
    $resolutionSection = '';

    if ($status === 'Resolved' && $resolutionNotes !== null && $resolutionNotes !== '') {
        $resolutionSection = '<p><strong>Resolution Notes:</strong><br>' . nl2br(e($resolutionNotes)) . '</p>';
        $message .= PHP_EOL . 'Resolution Notes: ' . $resolutionNotes;
    }

    $html = sprintf(
        '<p>Hello %s,</p><p>Your ticket <strong>%s</strong> is now marked as <strong>%s</strong>.</p>%s<p>Thank you.</p>',
        e($name),
        e($ticketCode),
        e($status),
        $resolutionSection
    );

    return [$subject, $html, $message];
}
