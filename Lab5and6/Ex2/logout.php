<?php
session_start();

// Destroy the user session to log the user out
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect the user back to the login page after logging out
header("Location: google_login.php"); // Redirect to the page with login options
exit();
?>