<?php
// Load environment variables
require_once 'env_loader.php';

// Database Configuration
$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'LibraryfiveDB';

// Create database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Google OAuth Configuration
define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID'] ?? '');
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET'] ?? '');
define('GOOGLE_REDIRECT_URI', $_ENV['GOOGLE_REDIRECT_URI'] ?? '');
define('GOOGLE_OAUTH_SCOPE', ['profile', 'email']);

// Path to Google API Client Library autoloader (if using Composer)
require_once 'vendor/autoload.php';

// Initialize Google Client
$gClient = new Google_Client();
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URI);
$gClient->addScope('email');
$gClient->addScope('profile');

// Session security settings
$secure = $_ENV['SESSION_SECURE'] ?? false;
$httponly = $_ENV['SESSION_HTTP_ONLY'] ?? true;

// Configure session settings
ini_set('session.cookie_httponly', $httponly);
ini_set('session.cookie_secure', $secure);
ini_set('session.use_only_cookies', 1);