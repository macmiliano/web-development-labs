<?php
$host = "localhost";
$user = "root";
$password = "YOUR_PASSWORD"; 
$dbname = "DATABASE_NAME";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
