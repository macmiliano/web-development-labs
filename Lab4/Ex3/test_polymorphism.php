<?php
require_once 'Book.php';
require_once 'Electronics.php';

// Create a book
$book = new Book("The Great Gatsby", 29.99, "F. Scott Fitzgerald", 1925, "Classic Literature");
echo "<h2>Book Details:</h2>";
$book->displayProduct();
$bookDiscount = $book->getDiscount();
echo "Discount: $" . number_format($bookDiscount, 2) . "<br>";
echo "Final Price: $" . number_format($book->getProductPrice() - $bookDiscount, 2) . "<br>";

echo "<hr>";

// Create an electronics item
$electronics = new Electronics("Smartphone", 499.99, "TechBrand", "X2000", 3);
echo "<h2>Electronics Details:</h2>";
$electronics->displayProduct();
$electronicsDiscount = $electronics->getDiscount();
echo "Discount: $" . number_format($electronicsDiscount, 2) . "<br>";
echo "Final Price: $" . number_format($electronics->getProductPrice() - $electronicsDiscount, 2) . "<br>";