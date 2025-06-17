<?php
$host = "localhost";
$user = "root";
$password = "Miliano"; // Default in XAMPP
$dbname = "StudentDB";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>