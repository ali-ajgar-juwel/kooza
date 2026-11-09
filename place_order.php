<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request";
    exit;
}

// Sanitize form inputs
$name = $conn->real_escape_string($_POST['name'] ?? '');
$address = $conn->real_escape_string($_POST['address'] ?? '');
$phone = $conn->real_escape_string($_POST['phone'] ?? '');
$payment = $conn->real_escape_string($_POST['payment'] ?? 'Cash on Delivery');

// Determine order items
$orderItems = [];
$total = 0;

// Case 1: Cart checkout
if (!empty($_SESSION['cart'])) {
    $orderItems = $_SESSION['cart'];
    foreach ($orderItems as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
// Case 2: Buy Now (single product)
elseif (!empty($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $productData = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

    if (!$productData) {
        echo "Product not found";
        exit;
    }

    $orderItems[$id] = [
        'name' => $productData['name'],
        'price' => $productData['price'],
        'quantity' => 1,
        'image' => $productData['image']
    ];

    $total = $productData['price'];
}
else {
    echo "No items to order";
    exit;
}

// Encode order items as JSON
$itemsJson = json_encode($orderItems);

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (name, address, phone, payment, items, total) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssd", $name, $address, $phone, $payment, $itemsJson, $total);

if ($stmt->execute()) {
    // Clear cart if it was cart checkout
    if (!empty($_SESSION['cart'])) {
        unset($_SESSION['cart']);
    }
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
