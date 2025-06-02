<?php
session_start();
require_once 'config.php';

$gClient->setPrompt('select_account');
$authUrl = $gClient->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login - Library System</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --background-color: #ecf0f1;
            --google-color: #4285f4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-header h2 {
            color: var(--primary-color);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .google-btn {
            display: inline-block;
            background: var(--google-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            margin: 1rem 0;
            transition: background-color 0.3s ease;
        }

        .google-btn:hover {
            background: #357abd;
        }

        .divider {
            margin: 1.5rem 0;
            text-align: center;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background-color: #ddd;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .links {
            margin-top: 1.5rem;
        }

        .links a {
            color: var(--secondary-color);
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome to Library System</h2>
            <p>Choose your login method</p>
        </div>

        <a href="<?php echo htmlspecialchars($authUrl); ?>" class="google-btn">
            Sign in with Google
        </a>

        <div class="divider">or</div>

        <div class="links">
            <a href="login.php">Login with Email</a>
            <span>|</span>
            <a href="register.php">Register New Account</a>
        </div>
    </div>
</body>
</html>