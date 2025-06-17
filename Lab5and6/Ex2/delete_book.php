<?php
session_start();
require_once 'config.php';
require_once 'auth_check.php';
require_once 'csrf_token.php';

// Check if user is authenticated
auth_check();

// Check if book ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: view_books.php");
    exit();
}

$book_id = (int)$_GET['id'];

// Get book details for confirmation
$stmt = $conn->prepare("SELECT title FROM Books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Book not found
    header("Location: view_books.php");
    exit();
}

$book = $result->fetch_assoc();
$book_title = $book['title'];

// Handle deletion
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    // Verify CSRF token
    verify_csrf_token();

    // Delete book from database
    $delete_stmt = $conn->prepare("DELETE FROM Books WHERE id = ?");
    $delete_stmt->bind_param("i", $book_id);

    if ($delete_stmt->execute()) {
        // Redirect to view books page with success message
        $_SESSION['message'] = "Book deleted successfully!";
        header("Location: view_books.php");
        exit();
    } else {
        $message = "Error deleting book: " . $delete_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Delete Book</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="library.php">Library</a></li>
                    <li><a href="view_books.php">View Books</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <?php if (!empty($message)): ?>
                <div class="message error"><?php echo $message; ?></div>
            <?php endif; ?>

            <section class="delete-confirmation">
                <h2>Confirm Deletion</h2>
                <p>Are you sure you want to delete the book: <strong><?php echo htmlspecialchars($book_title, ENT_QUOTES, 'UTF-8'); ?></strong>?</p>
                <p class="warning">This action cannot be undone!</p>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $book_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <button type="submit" name="confirm_delete" class="btn delete">Yes, Delete Book</button>
                    <a href="view_books.php" class="btn">Cancel</a>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Library Management System</p>
        </footer>
    </div>
</body>
</html>