<?php
$host = "localhost";
$user = "root";
$password = "YOUR_DATABASE_PASSWORD"; 
$dbname = "YOUR_DATABASE_NAME";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


