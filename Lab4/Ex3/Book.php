<?php
require_once 'Product.php';
require_once 'Discountable.php';

class Book extends Product implements Discountable {
    private $author;
    private $publication_year;
    private $genre;

    public function __construct($title, $price, $author, $publication_year, $genre) {
        parent::__construct($title, $price);
        $this->author = $author;
        $this->publication_year = $publication_year;
        $this->genre = $genre;
    }

    public function displayProduct() {
        parent::displayProduct();
        echo "Author: " . htmlspecialchars($this->author) . "<br>";
        echo "Publication Year: " . htmlspecialchars($this->publication_year) . "<br>";
        echo "Genre: " . htmlspecialchars($this->genre) . "<br>";
    }

    public function getDiscount() {
        // Books older than 10 years get a 20% discount
        $currentYear = date('Y');
        if (($currentYear - $this->publication_year) > 10) {
            return $this->product_price * 0.20;
        }
        return 0;
    }

    // Getters
    public function getAuthor() {
        return $this->author;
    }

    public function getPublicationYear() {
        return $this->publication_year;
    }

    public function getGenre() {
        return $this->genre;
    }

    // Setters
    public function setAuthor($author) {
        $this->author = $author;
    }

    public function setPublicationYear($year) {
        $this->publication_year = $year;
    }

    public function setGenre($genre) {
        $this->genre = $genre;
    }
}