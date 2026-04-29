CREATE DATABASE IF NOT EXISTS asus_support_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE asus_support_db;

CREATE TABLE IF NOT EXISTS requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ticket_id VARCHAR(12) NOT NULL UNIQUE,
  request_type ENUM('enquiry', 'repair') NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  country VARCHAR(100) NOT NULL,
  product_category ENUM('laptop', 'motherboard', 'gpu', 'monitor', 'peripherals', 'phone', 'desktop', 'other') NOT NULL,
  product_model VARCHAR(100) NOT NULL,
  serial_number VARCHAR(100) NULL,
  subject VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
  status ENUM('pending', 'in-review', 'in-progress', 'resolved', 'closed') NOT NULL DEFAULT 'pending',
  admin_notes TEXT NULL,
  file_path VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash)
VALUES ('admin', '$2y$10$kLqfI4hWfRlY0TRrM4l4jue6VQq01fR3GdKu8mCWUbSwzV2B4eQdG')
ON DUPLICATE KEY UPDATE username = username;
