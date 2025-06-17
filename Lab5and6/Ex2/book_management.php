<?php
//session_start();
require_once 'config.php';
require_once 'auth_check.php';
require_once 'Book.php';

auth_check();

$message = '';

if (isset($_POST['add_book'])) {
    $new_book = new Book(
        null,
        $_POST['title'],
        $_POST['price'],
        $_POST['author'],
        $_POST['publication_year'],
        $_POST['genre']
    );
    if ($new_book->save()) {
        $message = "<div class='alert success'>Book added successfully!</div>";
    } else {
        $message = "<div class='alert error'>Failed to add book.</div>";
    }
}

if (isset($_POST['update_book'])) {
    $book_id = $_POST['book_id'];
    $book_to_update = Book::find($book_id);
    if ($book_to_update) {
        $book_to_update->name = $_POST['title'];
        $book_to_update->price = $_POST['price'];
        $book_to_update->author = $_POST['author'];
        $book_to_update->publication_year = $_POST['publication_year'];
        $book_to_update->genre = $_POST['genre'];
        $book_to_update->available = isset($_POST['available']) ? true : false;

        if ($book_to_update->save()) {
            $message = "<div class='alert success'>Book updated successfully!</div>";
        } else {
            $message = "<div class='alert error'>Failed to update book.</div>";
        }
    } else {
        $message = "<div class='alert error'>Book not found for update.</div>";
    }
}

if (isset($_GET['delete_book_id'])) {
    $book_id_to_delete = $_GET['delete_book_id'];
    $book_to_delete = Book::find($book_id_to_delete);
    if ($book_to_delete && $book_to_delete->delete()) {
        $message = "<div class='alert success'>Book deleted successfully!</div>";
    } else {
        $message = "<div class='alert error'>Failed to delete book.</div>";
    }
}

$books = Book::getAll();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management - Library System</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --background-color: #ecf0f1;
            --card-background: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--primary-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem 0;
            border-bottom: 2px solid var(--secondary-color);
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .nav-links a {
            color: var(--secondary-color);
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-links a:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert.success {
            background-color: var(--success-color);
            color: white;
        }

        .alert.error {
            background-color: var(--error-color);
            color: white;
        }

        .card {
            background: var(--card-background);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary-color);
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: var(--error-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: var(--card-background);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .book-actions {
            display: flex;
            gap: 0.5rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Book Management</h1>
            <div class="nav-links">
                <a href="home.php">Home</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <?php echo $message; ?>

        <div class="card">
            <h2>Add New Book</h2>
            <form method="POST" action="book_management.php">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" required>
                </div>

                <div class="form-group">
                    <label for="publication_year">Publication Year</label>
                    <input type="number" id="publication_year" name="publication_year">
                </div>

                <div class="form-group">
                    <label for="genre">Genre</label>
                    <input type="text" id="genre" name="genre">
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" id="price" name="price" required>
                </div>

                <button type="submit" name="add_book" class="btn btn-primary">Add Book</button>
            </form>
        </div>

        <div class="card">
            <h2>Current Books</h2>
            <?php if (empty($books)): ?>
                <p>No books in the library.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Year</th>
                            <th>Genre</th>
                            <th>Price</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($book->product_id); ?></td>
                                <td><?php echo htmlspecialchars($book->name); ?></td>
                                <td><?php echo htmlspecialchars($book->author); ?></td>
                                <td><?php echo htmlspecialchars($book->publication_year); ?></td>
                                <td><?php echo htmlspecialchars($book->genre); ?></td>
                                <td>$<?php echo number_format($book->price, 2); ?></td>
                                <td><?php echo $book->available ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <div class="book-actions">
                                        <form method="POST" action="book_management.php">
                                            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book->product_id); ?>">
                                            <div class="form-group">
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($book->name); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($book->price); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="author" value="<?php echo htmlspecialchars($book->author); ?>">
                                            </div>
                                            <div class="form-group">
                                                <input type="number" name="publication_year" value="<?php echo htmlspecialchars($book->publication_year); ?>">
                                            </div>
                                            <div class="form-group">
                                                <input type="text" name="genre" value="<?php echo htmlspecialchars($book->genre); ?>">
                                            </div>
                                            <div class="checkbox-group">
                                                <input type="checkbox" name="available" <?php echo $book->available ? 'checked' : ''; ?>>
                                                <label>Available</label>
                                            </div>
                                            <button type="submit" name="update_book" class="btn btn-primary">Update</button>
                                        </form>
                                        <a href="book_management.php?delete_book_id=<?php echo htmlspecialchars($book->product_id); ?>" 
                                           onclick="return confirm('Are you sure you want to delete this book?');" 
                                           class="btn btn-danger">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>