-- FEureka database schema

CREATE DATABASE IF NOT EXISTS feureka
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE feureka;

-- Users stores registered users and administrators.
-- Users with role='User' are classified as either Student or Staff.
-- Student accounts store year_level and expiration_date for automatic account expiration.
-- Staff accounts do not expire automatically and may be removed manually by an administrator.
CREATE TABLE IF NOT EXISTS users (
    user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('User', 'Admin') NOT NULL DEFAULT 'User',
    user_type ENUM('Student', 'Staff') NULL,
    year_level TINYINT UNSIGNED NULL,
    expiration_date DATE NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories contains the predefined item categories used by found and missing reports.
CREATE TABLE IF NOT EXISTS categories (
    category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    PRIMARY KEY (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Found items are submitted by users, reviewed by admins, and become visible when approved.
-- user_id is nullable so historical records remain when expired user accounts are deleted.
-- processed_by references the administrator in users.user_id who last processed the record.
CREATE TABLE IF NOT EXISTS found_items (
    item_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NULL,
    category_id INT UNSIGNED NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    item_description TEXT NOT NULL,
    room VARCHAR(100) NULL,
    floor VARCHAR(50) NULL,
    location_description TEXT NULL,
    date_found DATE NOT NULL,
    status ENUM('Pending', 'Approved', 'Under Review', 'Claimed', 'Archived', 'Rejected') NOT NULL DEFAULT 'Pending',
    image VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_by INT UNSIGNED NULL,
    admin_notes TEXT NULL,
    PRIMARY KEY (item_id),
    KEY idx_found_items_user_id (user_id),
    KEY idx_found_items_category_id (category_id),
    KEY idx_found_items_processed_by (processed_by),
    CONSTRAINT fk_found_items_user
        FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE SET NULL,
    CONSTRAINT fk_found_items_category
        FOREIGN KEY (category_id) REFERENCES categories (category_id),
    CONSTRAINT fk_found_items_processed_by
        FOREIGN KEY (processed_by) REFERENCES users (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Missing reports are submitted by users to help admins match lost items with found records.
-- user_id is nullable so historical records remain when expired user accounts are deleted.
-- processed_by references the administrator in users.user_id who last processed the record.
CREATE TABLE IF NOT EXISTS missing_reports (
    report_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NULL,
    category_id INT UNSIGNED NOT NULL,
    item_name VARCHAR(150) NOT NULL,
    item_description TEXT NOT NULL,
    room VARCHAR(100) NULL,
    floor VARCHAR(50) NULL,
    location_description TEXT NULL,
    date_lost DATE NOT NULL,
    contact_number VARCHAR(30) NOT NULL,
    status ENUM('Open', 'Possible Match', 'Resolved', 'Archived') NOT NULL DEFAULT 'Open',
    image VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    processed_by INT UNSIGNED NULL,
    admin_notes TEXT NULL,
    PRIMARY KEY (report_id),
    KEY idx_missing_reports_user_id (user_id),
    KEY idx_missing_reports_category_id (category_id),
    KEY idx_missing_reports_processed_by (processed_by),
    CONSTRAINT fk_missing_reports_user
        FOREIGN KEY (user_id) REFERENCES users (user_id)
        ON DELETE SET NULL,
    CONSTRAINT fk_missing_reports_category
        FOREIGN KEY (category_id) REFERENCES categories (category_id),
    CONSTRAINT fk_missing_reports_processed_by
        FOREIGN KEY (processed_by) REFERENCES users (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
