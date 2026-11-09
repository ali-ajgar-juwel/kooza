<?php
session_start();
include 'config.php';

// Make sure form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize input
    $product_id = (int)$_POST['product_id'];
    $name = $conn->real_escape_string(trim($_POST['name']));
    $rating = (int)$_POST['rating'];
    $comment = $conn->real_escape_string(trim($_POST['comment']));

    // Validate required fields
    if (empty($name) || empty($rating) || empty($comment)) {
        $_SESSION['review_error'] = "All fields are required.";
        header("Location: product.php?id=$product_id");
        exit;
    }

    // Validate rating range
    if ($rating < 1 || $rating > 5) {
        $_SESSION['review_error'] = "Rating must be between 1 and 5.";
        header("Location: product.php?id=$product_id");
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO reviews (product_id, name, rating, comment) VALUES ($product_id, '$name', $rating, '$comment')";
    if ($conn->query($sql)) {
        $_SESSION['review_success'] = "Review submitted successfully!";
    } else {
        $_SESSION['review_error'] = "Error submitting review. Please try again.";
    }

    // Redirect back to the product page
    header("Location: product.php?id=$product_id");
    exit;
} else {
    // Invalid request
    header("Location: index.php");
    exit;
}
?>
