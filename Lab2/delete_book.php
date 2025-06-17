<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$sql = "DELETE FROM Books WHERE book_id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Book deleted. <a href='read_books.php'>Back to list</a>";
} else {
    echo "Error deleting book: " . $conn->error;
}
?>
