<?php
require_once 'Product.php';
require_once 'Discountable.php';

class Electronics extends Product implements Discountable {
    private $brand;
    private $model;
    private $warranty_months;

    public function __construct($name, $price, $brand, $model, $warranty_months) {
        parent::__construct($name, $price);
        $this->brand = $brand;
        $this->model = $model;
        $this->warranty_months = $warranty_months;
    }

    public function displayProduct() {
        parent::displayProduct();
        echo "Brand: " . htmlspecialchars($this->brand) . "<br>";
        echo "Model: " . htmlspecialchars($this->model) . "<br>";
        echo "Warranty: " . htmlspecialchars($this->warranty_months) . " months<br>";
    }

    public function getDiscount() {
        // Electronics with warranty less than 6 months get 15% discount
        if ($this->warranty_months < 6) {
            return $this->product_price * 0.15;
        }
        return 0;
    }
}