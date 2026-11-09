<?php
session_start();
include 'config.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Fetch product from database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // If cart session doesn't exist, create it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If product already in cart, increment quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }

        echo $product['name'] . " added to cart!";
    } else {
        echo "Product not found!";
    }
} else {
    echo "No product ID provided!";
}
