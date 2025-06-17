<?php
session_start();
require_once 'config.php'; // Include the configuration file

if (isset($_GET['code'])) {
    $gClient->authenticate($_GET['code']);
    $_SESSION['access_token'] = $gClient->getAccessToken();

    // Get user's profile info
    $oauth2 = new Google_Service_Oauth2($gClient);
    $userInfo = $oauth2->userinfo->get();

    $google_id = $userInfo->id;
    $email = $userInfo->email;
    $name = $userInfo->name; // User's full name

    // Check if user exists in your database (e.g., 'users' table)
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE google_id = ? OR email = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("ss", $google_id, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, log them in
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username']; // Or use Google name if no username
            $_SESSION['google_logged_in'] = true; // Flag for Google login
            header("Location: home.php"); // Redirect to home page
            exit();
        } else {
            // New user, register them (you might want a separate registration flow or auto-register)
            $username = $name; // Use Google name as username, or prompt user
            $hashed_password = ''; // No password needed for Google OAuth, but db schema might require it

            $stmt_insert = $conn->prepare("INSERT INTO users (google_id, username, email, password) VALUES (?, ?, ?, ?)");
            if ($stmt_insert) {
                $placeholder_password = password_hash(uniqid('google_'), PASSWORD_DEFAULT); // Unique placeholder
                $stmt_insert->bind_param("ssss", $google_id, $username, $email, $placeholder_password);
                if ($stmt_insert->execute()) {
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['google_logged_in'] = true;
                    header("Location: home.php");
                    exit();
                } else {
                    echo "Error registering new user: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            } else {
                echo "Error preparing insert statement: " . $conn->error;
            }
        }
        $stmt->close();
    } else {
        echo "Error preparing select statement: " . $conn->error;
    }
} else {
    echo "Google authentication failed or no code received.";
    // Add a link to go back to the login page
    echo "<br><br><a href='google_login.php'>Return to Login Page</a>";
}

$conn->close();
?>