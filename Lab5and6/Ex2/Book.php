<?php

require_once 'config.php'; // Updated to use config.php for DB connection in Lab 5 context
require_once 'Product.php';
require_once 'Discountable.php';

class Book extends Product implements Discountable {
    public $author;
    public $publication_year;
    public $genre;
    public $available; // New property for availability

    public function __construct($product_id, $name, $price, $author, $publication_year, $genre, $available = true) {
        parent::__construct($product_id, $name, $price);
        $this->author = $author;
        $this->publication_year = $publication_year;
        $this->genre = $genre;
        $this->available = $available;
    }

    public function getInfo() {
        $baseInfo = parent::getInfo();
        return $baseInfo . ", Author: " . $this->author . ", Publication Year: " . $this->publication_year . ", Genre: " . $this->genre . ", Available: " . ($this->available ? 'Yes' : 'No');
    }

    public function applyDiscount($percentage) {
        if ($percentage > 0 && $percentage <= 100) {
            $this->price = $this->price * (1 - ($percentage / 100));
        }
    }

    // --- Database Methods ---

    public function save() {
        global $conn; // Access the database connection

        if ($this->product_id === null) { // New book
            $stmt = $conn->prepare("INSERT INTO books (title, author, publication_year, genre, price, available) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisds", $this->name, $this->author, $this->publication_year, $this->genre, $this->price, $this->available);
        } else { // Existing book (update)
            $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, publication_year = ?, genre = ?, price = ?, available = ? WHERE id = ?");
            $stmt->bind_param("ssisdsi", $this->name, $this->author, $this->publication_year, $this->genre, $this->price, $this->available, $this->product_id);
        }

        if ($stmt->execute()) {
            if ($this->product_id === null) {
                $this->product_id = $conn->insert_id; // Set the new ID for new books
            }
            return true;
        } else {
            echo "Error saving book: " . $stmt->error;
            return false;
        }
        $stmt->close();
    }

    public static function find($book_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT id, title, author, publication_year, genre, price, available FROM books WHERE id = ?");
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $data = $result->fetch_assoc();
            return new Book(
                $data['id'],
                $data['title'],
                $data['price'],
                $data['author'],
                $data['publication_year'],
                $data['genre'],
                (bool)$data['available']
            );
        }
        return null;
        $stmt->close();
    }

    public static function getAll() {
        global $conn;
        $books = [];
        $result = $conn->query("SELECT id, title, author, publication_year, genre, price, available FROM books");

        if ($result->num_rows > 0) {
            while ($data = $result->fetch_assoc()) {
                $books[] = new Book(
                    $data['id'],
                    $data['title'],
                    $data['price'],
                    $data['author'],
                    $data['publication_year'],
                    $data['genre'],
                    (bool)$data['available']
                );
            }
        }
        return $books;
    }

    public function delete() {
        global $conn;
        if ($this->product_id !== null) {
            $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
            $stmt->bind_param("i", $this->product_id);
            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error deleting book: " . $stmt->error;
                return false;
            }
            $stmt->close();
        }
        return false;
    }
}

?>