<?php
include 'Book.php';

$book = new Book(
    'No excuse for Failure',
    'Bessong Maxime',
    2025,
    'Self-help',
    20.99
);

 $book->displayBookInfo();
?>