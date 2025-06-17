<?php
// Database configuration
require_once 'config.php';

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    google_id VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully or already exists";
} else {
    echo "Error creating users table: " . $conn->error;
}

// Create an admin user if it doesn't exist
$admin_username = "admin";
$admin_email = "admin@example.com";
$admin_password = password_hash("admin123", PASSWORD_DEFAULT); // Change this password in production

$check_admin = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$check_admin->bind_param("s", $admin_username);
$check_admin->execute();
$result = $check_admin->get_result();

if ($result->num_rows == 0) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $admin_username, $admin_email, $admin_password);

    if ($stmt->execute()) {
        echo "<br>Admin user created successfully";
    } else {
        echo "<br>Error creating admin user: " . $stmt->error;
    }
    $stmt->close();
}
$check_admin->close();

$conn->close();
?>