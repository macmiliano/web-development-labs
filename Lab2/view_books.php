<?php include 'db.php'; ?>
<?php

$sql = "SELECT b.book_title, a.author_name, b.genre, b.price 
        FROM Books b 
        INNER JOIN Authors a ON b.author_id = a.author_id";
$result = $conn->query($sql);
?>

<h2>Library Books</h2>
<table>
    <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Genre</th>
        <th>Price</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['book_title'] ?></td>
        <td><?= $row['author_name'] ?></td>
        <td><?= $row['genre'] ?></td>
        <td><?= number_format($row['price'], 2) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
