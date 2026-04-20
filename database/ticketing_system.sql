CREATE DATABASE IF NOT EXISTS ticketing_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ticketing_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_code VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    department VARCHAR(100) NOT NULL,
    issue_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    resolution_notes TEXT NULL,
    priority ENUM('Low', 'Medium', 'High') NOT NULL,
    status ENUM('Pending', 'Ongoing', 'Ready', 'Resolved', 'Cancelled') NOT NULL DEFAULT 'Pending',
    cancel_reason ENUM('Duplicate', 'Expired') NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_resolution_required
        CHECK (status <> 'Resolved' OR (resolution_notes IS NOT NULL AND CHAR_LENGTH(TRIM(resolution_notes)) >= 10))
);

-- Migration support for existing databases:
ALTER TABLE tickets
ADD COLUMN IF NOT EXISTS resolution_notes TEXT NULL AFTER description;

-- Optional (MySQL 8.0.16+): enforce resolution note requirement at DB level
-- ALTER TABLE tickets
-- ADD CONSTRAINT chk_resolution_required
-- CHECK (status <> 'Resolved' OR (resolution_notes IS NOT NULL AND CHAR_LENGTH(TRIM(resolution_notes)) >= 10));

CREATE TABLE IF NOT EXISTS ticket_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, role)
VALUES ('admin', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE username = username;

-- Default admin credentials:
-- username: admin
-- password: admin123
