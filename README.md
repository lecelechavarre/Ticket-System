# Ticketing System (PHP + MySQL + XAMPP)         
 
## 1) Requirements
- XAMPP (Apache + MySQL)
- PHP 8.1+
- MySQL via phpMyAdmin
- Optional for email: Composer + PHPMailer

## 2) Database Setup
1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin (`http://localhost/phpmyadmin`).
3. Import `database/ticketing_system.sql`.
4. This creates:
   - `users`
   - `tickets`
   - `ticket_logs`
5. Default admin account:
   - Username: `admin`
   - Password: `admin123`
   - On first successful login, the password is auto-hashed.

## 3) App URLs
- Public ticket form: `http://localhost/ticket-system/user/`
- Admin entry: `http://localhost/ticket-system/admin/`
- Root redirect: `http://localhost/ticket-system/`
- Separation:
  - `/user/` exposes only ticket submission
  - `/admin/` requires authenticated admin session

## 4) Email Integration (PHPMailer)
1. Open terminal in project root.
2. Install dependency:
   - `composer require phpmailer/phpmailer`
3. Edit `mail/mailer.php`:
   - `MAIL_USE_SMTP = true`
   - Set `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_SECURE`
   - Set `MAIL_FROM_EMAIL`, `MAIL_FROM_NAME`
4. Email notifications are triggered when ticket status changes to:
   - `Ongoing`
   - `Ready`
   - `Resolved`

## 5) Security Implemented
- PDO prepared statements
- Session-based admin auth
- Password hashing via `password_hash` / `password_verify`
- CSRF protection on forms
- Input sanitization and output escaping
- Basic logging to `logs/system.log`

## 6) Main Workflow
1. User submits ticket (saved as `Pending`).
2. Admin reviews `Pending` and clicks `Get Ticket` (`Ongoing`).
3. Admin can set:
   - `Ready for Remote`
   - `Resolve` (closed as `Resolved`) with required resolution notes
   - `Mark as Duplicate` / `Mark as Expired` (closed as `Cancelled`)
4. Closed view supports filtering by:
   - Date range
   - Department
   - Issue type

## 7) Resolution Notes Requirement
- Database includes `tickets.resolution_notes`.
- Backend blocks resolution when notes are missing or too short.
- Resolved notification email includes ticket ID, status, and resolution notes.
- Closed tickets display resolution notes for auditability.
