CREATE DATABASE IF NOT EXISTS cv_database;

USE cv_database;

CREATE TABLE IF NOT EXISTS cv_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    job_title VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    address TEXT,
    skills TEXT,
    languages TEXT,
    hobbies TEXT,
    profile_summary TEXT,
    work_experience TEXT,
    education TEXT,
    photo VARCHAR(255)
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert initial admin user (password is 'admin123' hashed)
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$qLcoQ9BSc8NZz3PQtbNHMe9egK2lTxBRHD7hK/4CMaUqKYCAnVvoC');

