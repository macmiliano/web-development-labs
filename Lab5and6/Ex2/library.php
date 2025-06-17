<?php
session_start();
require_once 'config.php';
require_once 'auth_check.php';

// Check if user is authenticated
auth_check();

// Page title
$page_title = "Library Management";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $page_title; ?></h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="library-actions">
                <h2>Library Actions</h2>
                <div class="action-buttons">
                    <a href="add_book.php" class="btn">Add New Book</a>
                    <a href="view_books.php" class="btn">View All Books</a>
                </div>
            </section>

            <section class="recent-books">
                <h2>Recently Added Books</h2>
                <?php
                // Get the 5 most recently added books
                $sql = "SELECT * FROM Books ORDER BY id DESC LIMIT 5";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    echo "<div class='books-container'>";
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='book-card'>";
                        echo "<h3>" . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . "</h3>";
                        echo "<p>Author: " . htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<p>Genre: " . htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8') . "</p>";
                        echo "<div class='book-actions'>";
                        echo "<a href='edit_book.php?id=" . (int)$row['id'] . "' class='btn'>Edit</a>";
                        echo "<a href='delete_book.php?id=" . (int)$row['id'] . "' class='btn delete'>Delete</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p>No books found in the library.</p>";
                }
                ?>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date("Y"); ?> Library Management System</p>
        </footer>
    </div>
</body>
</html>