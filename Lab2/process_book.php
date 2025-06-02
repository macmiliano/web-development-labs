<?php include 'db.php'; ?>
<?php

$book_title = $_POST['book_title'];
$author_id = $_POST['author_id'];
$genre = $_POST['genre'];
$price = $_POST['price'];

if (!is_numeric($price)) {
    die("Error: Price must be a valid number.");
}

$stmt = $conn->prepare("INSERT INTO Books (book_title, author_id, genre, price) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sisd", $book_title, $author_id, $genre, $price);
$stmt->execute();

echo "Book added successfully!";
echo "<br><a href='view_books.php'>View Books</a>";
?>
