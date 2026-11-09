<?php
session_start();
if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
    $id = $_GET['id'];
    $change = intval($_GET['change']);
    $_SESSION['cart'][$id]['quantity'] += $change;
    if ($_SESSION['cart'][$id]['quantity'] <= 0) {
        unset($_SESSION['cart'][$id]);
    }
}
echo "Cart updated";
