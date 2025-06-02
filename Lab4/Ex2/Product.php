<?php
class Product {
    protected $product_name;
    protected $product_price;

    public function __construct($name, $price) {
        $this->product_name = $name;
        $this->product_price = $price;
    }

    public function displayProduct() {
        echo "<h2>Product Information</h2>";
        echo "Name: " . htmlspecialchars($this->product_name) . "<br>";
        echo "Price: $" . number_format($this->product_price, 2) . "<br>";
    }

    // Getters and Setters
    public function getProductName() {
        return $this->product_name;
    }

    public function getProductPrice() {
        return $this->product_price;
    }

    public function setProductName($name) {
        $this->product_name = $name;
    }

    public function setProductPrice($price) {
        $this->product_price = $price;
    }
}