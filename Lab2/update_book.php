<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $price = $_POST['price'];

    $sql = "UPDATE Books SET 
            title='$title', author='$author', 
            year=$year, genre='$genre', price=$price 
            WHERE book_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Book updated! <a href='read_books.php'>Back to list</a>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    $result = $conn->query("SELECT * FROM Books WHERE book_id=$id");
    $row = $result->fetch_assoc();
?>
    <h2>Edit Book</h2>
    <form method="POST">
        Title: <input type="text" name="title" value="<?= $row['title'] ?>" required><br>
        Author: <input type="text" name="author" value="<?= $row['author'] ?>" required><br>
        Year: <input type="number" name="year" value="<?= $row['year'] ?>" required><br>
        Genre: <input type="text" name="genre" value="<?= $row['genre'] ?>" required><br>
        Price: <input type="text" name="price" value="<?= $row['price'] ?>" required><br>
        <input type="submit" value="Update Book">
    </form>
<?php } ?>
