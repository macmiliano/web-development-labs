<?php
require_once 'auth_check.php';
auth_check(); // Protect this page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System - Home</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --background-color: #ecf0f1;
            --text-color: #2c3e50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .navbar {
            background-color: var(--primary-color);
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: var(--secondary-color);
        }

        .container {
            max-width: 1200px;
            margin: 80px auto 0;
            padding: 2rem;
        }

        .welcome-section {
            text-align: center;
            padding: 3rem 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-card h3 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .logout-btn {
            background-color: var(--accent-color);
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="book_management.php">Books</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <p>Manage your library activities efficiently with our digital platform.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <h3>Book Management</h3>
                <p>Add, update, or remove books from your library collection.</p>
                <a href="book_management.php" class="btn">Manage Books</a>
            </div>

            <div class="feature-card">
                <h3>User Profile</h3>
                <p>View and update your profile information.</p>
                <a href="profile.php" class="btn">View Profile</a>
            </div>

            <?php if (isset($_SESSION['google_logged_in']) && $_SESSION['google_logged_in']): ?>
            <div class="feature-card">
                <h3>Google Integration</h3>
                <p>Your account is connected with Google.</p>
            </div>
            <?php endif; ?>

            <div class="feature-card">
                <h3>Library Stats</h3>
                <p>View library statistics and analytics.</p>
                <a href="#" class="btn">View Stats</a>
            </div>
        </div>
    </div>
</body>
</html>