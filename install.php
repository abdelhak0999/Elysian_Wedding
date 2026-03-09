<?php
// Installation script for Wedding Planner
echo "=== Wedding Planner Installation ===\n";

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'wedding_planner';

// Try to connect to MySQL
echo "Connecting to MySQL... ";
try {
    $conn = new mysqli($db_host, $db_user, $db_pass);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "SUCCESS\n";
} catch (Exception $e) {
    echo "FAILED\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Create database
echo "Creating database... ";
$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if ($conn->query($sql) === TRUE) {
    echo "SUCCESS\n";
} else {
    echo "FAILED\n";
    echo "Error creating database: " . $conn->error . "\n";
    exit(1);
}

// Select database
$conn->select_db($db_name);

// Create users table
echo "Creating users table... ";
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

if ($conn->query($sql) === TRUE) {
    echo "SUCCESS\n";
} else {
    echo "FAILED\n";
    echo "Error creating table: " . $conn->error . "\n";
    exit(1);
}

// Check if default admin user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = 'admin@demo.com'");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Creating default users... ";
    
    // Default admin password (hashed)
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $user_password = password_hash('password123', PASSWORD_DEFAULT);
    
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
    
    $success = true;
    foreach ($default_users as $user) {
        $stmt->bind_param("sssss", $user[0], $user[1], $user[2], $user[3], $user[4]);
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }
    
    if ($success) {
        echo "SUCCESS\n";
    } else {
        echo "FAILED\n";
        echo "Error creating default users: " . $stmt->error . "\n";
    }
} else {
    echo "Default users already exist.\n";
}

echo "\nInstallation complete!\n";
echo "Default admin login: admin@demo.com / admin123\n";
echo "Default user login: user@demo.com / password123\n";
echo "\nPlease delete this file after installation for security reasons.\n";
?>
