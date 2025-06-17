<?php
require_once 'config.php';

// Add created_at column to users table if it doesn't exist
$sql = "SHOW COLUMNS FROM users LIKE 'created_at'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE users ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
    if ($conn->query($sql) === TRUE) {
        echo "Column created_at added successfully";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column created_at already exists";
}

$conn->close();
?>