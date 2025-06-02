<?php
require_once 'Product.php';

class Book extends Product {
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