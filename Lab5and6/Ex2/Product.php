<?php

class Product {
    // Properties
    public $product_id;
    public $name;
    public $price;

    // Constructor
    public function __construct($product_id, $name, $price) {
        $this->product_id = $product_id;
        $this->name = $name;
        $this->price = $price;
    }

    // Method to get product info
    public function getInfo() {
        return "Product ID: " . $this->product_id . ", Name: " . $this->name . ", Price: $" . number_format($this->price, 2);
    }
}

?>