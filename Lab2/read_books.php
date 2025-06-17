<?php include 'db.php'; ?>

<h2>All Books</h2>
<a href="create_book.php">Add New Book</a><br><br>

<table cellpadding="8">
    <tr>
        <th>ID</th><th>Title</th><th>Author</th><th>Year</th>
        <th>Genre</th><th>Price</th><th>Actions</th>
    </tr>

    <?php
    $result = $conn->query("SELECT * FROM Books");
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['book_id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['author']}</td>
                <td>{$row['year']}</td>
                <td>{$row['genre']}</td>
                <td>{$row['price']}</td>
                <td>
                    <a href='update_book.php?id={$row['book_id']}'>Edit</a> |
                    <a href='delete_book.php?id={$row['book_id']}'>Delete</a>
                </td>
              </tr>";
    }
    ?>
</table>
