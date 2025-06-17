<?php
session_start();
require_once 'config.php';
require_once 'auth_check.php';

// Check if user is authenticated
auth_check();

// Get user information
$user_id = $_SESSION['user_id'];
$user = null;

// Fix the query to include created_at or remove it from the field list
$stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

// Handle profile update
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Validate input
    if (empty($username) || empty($email)) {
        $message = "Username and email are required.";
    } else {
        // Update user profile
        $update_stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $update_stmt->bind_param("ssi", $username, $email, $user_id);

        if ($update_stmt->execute()) {
            $message = "Profile updated successfully!";
            // Update session data
            $_SESSION['username'] = $username;
            // Refresh user data
            $user['username'] = $username;
            $user['email'] = $email;
        } else {
            $message = "Error updating profile: " . $update_stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>User Profile</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="library.php">Library</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <section class="profile-info">
                <h2>Profile Information</h2>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Member Since:</strong> <?php echo htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8'); ?></p>
            </section>

            <section class="update-profile">
                <h2>Update Profile</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password (leave blank to keep current):</label>
                        <input type="password" id="new_password" name="new_password">
                    </div>

                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Library Management System</p>
        </footer>
    </div>
</body>
</html>