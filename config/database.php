<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'wedding_planner';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === FALSE) {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($db_name);

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

// Check if default admin user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = 'admin@demo.com'");
$stmt->execute();
$result = $stmt->get_result();

// If admin user doesn't exist, create default users
if ($result->num_rows == 0) {
    // Default admin password (hashed)
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $user_password = password_hash('password', PASSWORD_DEFAULT);
    
    // Insert default admin user
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
    
    // Default users
    $default_users = [
        ['Admin', 'System', 'admin@demo.com', $admin_password, 'admin'],
        ['Salah', 'Demo', 'salah@demo.com', $user_password, 'admin'],
        ['Hadil', 'Demo', 'hadil@demo.com', $user_password, 'admin'],
        ['Hiba', 'Demo', 'hiba@demo.com', $user_password, 'admin'],
        ['Lidya', 'Demo', 'lidya@demo.com', $user_password, 'admin'],
        ['Hamzi', 'Demo', 'hamzi@demo.com', $user_password, 'admin'],
        ['Hani', 'Demo', 'hani@demo.com', $user_password, 'admin'],
        ['Utilisateur', 'Demo', 'user@demo.com', $user_password, 'user']
    ];
    
    foreach ($default_users as $user) {
        $stmt->bind_param("sssss", $user[0], $user[1], $user[2], $user[3], $user[4]);
        $stmt->execute();
    }
}
