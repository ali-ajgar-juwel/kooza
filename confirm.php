<?php
session_start();
include 'config.php';

$product = null;
$total = 0;

// --- Buy Now (POST product_id) ---
if (isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];
    $row = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
    if (!$row) {
        header("Location: index.php");
        exit;
    }
    $product = [
        $id => [
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => 1,
            'image' => $row['image']
        ]
    ];
    $total = $row['price'];
}
// --- Cart Checkout ---
elseif (!empty($_SESSION['cart'])) {
    $product = $_SESSION['cart'];
    foreach ($product as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout - Kooza</title>
<link rel="stylesheet" href="cart.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Original style maintained */
.checkout-form { max-width:500px; margin:20px auto; padding:20px; background:#f4f4f4; border-radius:10px; position:relative; }
.checkout-form h2 { text-align:center; margin-bottom:20px; }
.checkout-form input, .checkout-form select { width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc; }
.order-summary { background:#fff; padding:15px; border-radius:8px; margin-bottom:20px; }
.order-summary h3 { margin-bottom:10px; }
.order-summary p { margin:5px 0; }
.confirm-btn { width:100%; padding:12px; background:#000; color:#fff; border:none; border-radius:8px; font-size:16px; cursor:pointer; }

/* Popup */
.popup { position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); display:none; justify-content:center; align-items:center; z-index:1000; }
.popup-content { background:#fff; padding:30px; border-radius:10px; text-align:center; max-width:400px; width:90%; }
.popup-content button { margin-top:20px; padding:10px 20px; border:none; background:#000; color:#fff; border-radius:5px; cursor:pointer; }

/* Back Button Original Style */
.back-btn { position:absolute; top:-10px; left:-10px; background:#000; color:#fff; padding:10px 15px; border-radius:5px; text-decoration:none; font-size:16px; display:flex; align-items:center; gap:5px; z-index:10; }
.back-btn:hover { background:#333; }
</style>
</head>
<body>

<section class="checkout-form">

<a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>

<h2>Checkout</h2>

<form id="checkoutForm" method="POST">

    <input type="text" name="name" placeholder="Full Name" required>
    <input type="text" name="address" placeholder="Address" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <input type="text" name="payment" value="Cash on Delivery" readonly>

    <div class="order-summary">
        <h3>Your Order</h3>
        <?php foreach ($product as $item): ?>
            <p><?php echo $item['name'] . " x " . $item['quantity'] . " - $" . ($item['price']*$item['quantity']); ?></p>
        <?php endforeach; ?>
        <p><strong>Total: $<?php echo $total; ?></strong></p>
    </div>

    <?php if (isset($_POST['product_id'])): ?>
        <input type="hidden" name="product_id" value="<?php echo (int)$_POST['product_id']; ?>">
    <?php endif; ?>

    <button type="submit" class="confirm-btn">Confirm Order</button>
</form>
</section>

<div class="popup" id="popup">
    <div class="popup-content">
        <p>Order placed successfully!</p>
        <button onclick="closePopup()">OK</button>
    </div>
</div>

<script>
const form = document.getElementById('checkoutForm');
form.addEventListener('submit', function(e){
    e.preventDefault();
    fetch('place_order.php', {
        method:'POST',
        body: new FormData(form)
    }).then(res => res.text()).then(data=>{
        document.getElementById('popup').style.display='flex';
        fetch('clear_cart.php'); // clear cart if any
    });
});

function closePopup(){
    document.getElementById('popup').style.display='none';
    window.location.href='index.php';
}
</script>

</body>
</html>
