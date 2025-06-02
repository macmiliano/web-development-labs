<?php
// Database Configuration
$servername = "localhost";
$username = "your_username"; // Replace with your MySQL username
$password = "your_password"; // Replace with your MySQL password
$dbname = "your_database"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create Books table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Books (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    year INT(4) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    // Table created successfully or already exists
} else {
    echo "Error creating Books table: " . $conn->error;
}

// Create Users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    google_id VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    // Table created successfully or already exists
} else {
    echo "Error creating users table: " . $conn->error;
}

// Google OAuth Configuration
define('GOOGLE_CLIENT_ID', 'your_client_id'); // Replace with your Client ID
define('GOOGLE_CLIENT_SECRET', 'your_client_secret'); // Replace with your Client Secret
define('GOOGLE_REDIRECT_URI', 'http://localhost/your_path/google_callback.php'); // Replace with your redirect URI
define('GOOGLE_OAUTH_SCOPE', ['profile', 'email']);

// Path to Google API Client Library autoloader (if using Composer)
require_once 'vendor/autoload.php';

// Initialize Google Client
$gClient = new Google_Client();
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URI);
$gClient->addScope(GOOGLE_OAUTH_SCOPE);