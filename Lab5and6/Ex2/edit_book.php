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

// Get book details
$stmt = $conn->prepare("SELECT * FROM Books WHERE id = ?");
$stmt->bind_param("i", $book_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Book not found
    header("Location: view_books.php");
    exit();
}

$book = $result->fetch_assoc();

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_book'])) {
    // Verify CSRF token
    verify_csrf_token();

    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $price = $_POST['price'];
    $genre = $_POST['genre'];
    $year = $_POST['year'];

    // Update book in database
    $update_stmt = $conn->prepare("UPDATE Books SET title = ?, author = ?, price = ?, genre = ?, year = ? WHERE id = ?");
    $update_stmt->bind_param("ssdsii", $title, $author, $price, $genre, $year, $book_id);

    if ($update_stmt->execute()) {
        $message = "Book updated successfully!";

        // Refresh book data
        $stmt->execute();
        $result = $stmt->get_result();
        $book = $result->fetch_assoc();
    } else {
        $message = "Error updating book: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Edit Book</h1>
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
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <section class="edit-book-form">
                <h2>Edit Book Details</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $book_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="author">Author:</label>
                        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($book['price'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="genre">Genre:</label>
                        <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="year">Year:</label>
                        <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($book['year'], ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <button type="submit" name="update_book" class="btn">Update Book</button>
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