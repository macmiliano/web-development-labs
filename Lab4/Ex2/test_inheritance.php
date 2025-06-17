<?php
require_once 'Product.php';
require_once 'Book.php';

// Create a generic product
$product = new Product("Generic Item", 19.99);
echo "<h2>Testing Generic Product:</h2>";
$product->displayProduct();

echo "<hr>";

// Create a book
$book = new Book("Max The GREATEST", 9.99, "F. Scott Fitzgerald", 1925, "Classic Literature");
echo "<h2>Testing Book Product:</h2>";
$book->displayProduct();