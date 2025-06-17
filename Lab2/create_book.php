<?php include 'db.php'; ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $price = $_POST['price'];

    if (!is_numeric($price) || $price < 0 || !is_numeric($year)) {
        die("Invalid data. Price and year must be numbers.");
    }

    $sql = "INSERT INTO Books (title, author, year, genre, price)
            VALUES ('$title', '$author', $year, '$genre', $price)";
    
    if ($conn->query($sql) === TRUE) {
        echo "Book added successfully! <a href='read_books.php'>View All Books</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- <h2>Add a Book</h2>
<form method="POST" action="create_book.php">
    Title: <input type="text" name="title" required><br>
    Author: <input type="text" name="author" required><br>
    Year: <input type="number" name="year" required><br>
    Genre: <input type="text" name="genre" required><br>
    Price: <input type="text" name="price" required><br>
    <input type="submit" value="Add Book">
</form> -->

<h2>Add a Book</h2>
<form method="POST" action="create_book.php" onsubmit="return validateForm();">
    Title: <input type="text" name="title" required><br><br>
    
    Author: <input type="text" name="author" required><br><br>
    
    Year: <input type="number" name="year" min="1000" max="9999" required><br><br>
    
    Genre: <input type="text" name="genre" required><br><br>
    
    Price: <input type="number" name="price" step="0.01" min="0" required><br><br>
    
    <input type="submit" value="Add Book">
</form>

<script>
function validateForm() {
    const price = parseFloat(document.forms[0]["price"].value);
    const year = parseInt(document.forms[0]["year"].value);

    if (isNaN(price) || price < 0) {
        alert("Please enter a valid positive price.");
        return false;
    }

    if (isNaN(year) || year < 1000 || year > new Date().getFullYear()) {
        alert("Please enter a valid publication year.");
        return false;
    }

    return true;
}
</script>
