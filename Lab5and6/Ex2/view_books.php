<?php
// Include database connection
require_once 'config.php';

// Fetch all books from the database
$sql = "SELECT id, title, author, price, genre, year FROM books";
$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Error fetching books: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Books</title>
    <!-- Add your CSS here -->
</head>
<body>
    <h1>Book List</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Price</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['price'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($row['year'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a href="edit_book.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>">Edit</a> | 
                            <a href="delete_book.php?id=<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No books found in the database.</p>
    <?php endif; ?>

    <p><a href="add_book.php">Add New Book</a></p>
    <p><a href="home.php">Back to Home</a></p>

    <?php $conn->close(); ?>
</body>
</html>