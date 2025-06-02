<?php include 'db.php'; ?>

<?php
$result = $conn->query("SELECT * FROM Authors");
?>

<form method="POST" action="process_book.php">
    Title: <input type="text" name="book_title" required><br>
    Author: 
    <select name="author_id" required>
        <option value="">--Select Author--</option>
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['author_id'] ?>"><?= $row['author_name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    Genre: <input type="text" name="genre" required><br>
    Price: <input type="text" name="price" required><br>
    <input type="submit" value="Add Book">
</form>
