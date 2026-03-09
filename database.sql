-- Create database
CREATE DATABASE IF NOT EXISTS wedding_planner;
USE wedding_planner;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default users
INSERT INTO users (firstname, lastname, email, password, role) VALUES
('Admin', 'System', 'admin@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Salah', 'Demo', 'salah@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Hadil', 'Demo', 'hadil@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Hiba', 'Demo', 'hiba@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Lidya', 'Demo', 'lidya@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Hamzi', 'Demo', 'hamzi@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Hani', 'Demo', 'hani@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'admin'),
('Utilisateur', 'Demo', 'user@demo.com', '$2y$10$qJxFPrKqGQZgHRFrQfB1eeYGu7AYVXxBLqTYIBc.EgQVXlBxfgBwK', 'user');

-- Note: The password hash above corresponds to 'password123' for all users
-- In a real application, each user would have a unique password hash
