<?php

class Book
{
    public $title;
    public $author;
    public $publication_year;
    public $genre;
    public $price;

    public function __construct($title, $author, $publication_year, $genre, $price)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publication_year = $publication_year;
        $this->genre = $genre;
        $this->price = $price;
    }

    public function displayBookInfo()
    {
        echo "Title: {$this->title}<br>";
        echo "Author: {$this->author}<br>";
        echo "Publication Year: {$this->publication_year}<br>";
        echo "Genre: {$this->genre}<br>";
        echo "Price: \${$this->price}<br>";
    }
}

?>