<?php
session_start();

// This file should be included on every page that needs to be restricted to logged-in users
function auth_check() {
    // Verify if a user is logged in (either by traditional login or Google OAuth)
    if (!isset($_SESSION['user_id'])) {
        // If not logged in, redirect the user to login.php or google_login.php
        header("Location: google_login.php"); // Redirect to a page that offers login options
        exit();
    }
}
?>