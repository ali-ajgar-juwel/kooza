<?php
session_start();
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart - Kooza Clothes Shop</title>
  <link rel="stylesheet" href="cart.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <header class="header">
    <img src="images/logo.png" alt="Logo" class="logo">

  </header>

  <section class="cart-section">
    <h2>Your Cart</h2>

    <div class="cart-items">
      <?php
      $total = 0;
      if (!empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $id => $item) {
              $item_total = $item['price'] * $item['quantity'];
              $total += $item_total;
              echo '
              <div class="cart-item">
                <div class="cart-left">
                  <img src="'.$item['image'].'" alt="'.$item['name'].'">
                  <h3>'.$item['name'].'</h3>
                </div>
                <div class="cart-right">
                  <button class="remove" onclick="removeFromCart('.$id.')"><i class="fas fa-trash"></i></button>
                  <div class="quantity">
                    <button class="minus" onclick="updateQuantity('.$id.', -1)"><i class="fas fa-minus"></i></button>
                    <span>'.$item['quantity'].'</span>
                    <button class="plus" onclick="updateQuantity('.$id.', 1)"><i class="fas fa-plus"></i></button>
                  </div>
                  <p>$'.$item_total.'</p>
                </div>
              </div>';
          }
      } else {
          echo "<p>Your cart is empty.</p>";
      }
      ?>
    </div>

    <div class="cart-summary">
      <p>Total: <span>$<?php echo $total; ?></span></p>
      <a href="confirm.php"><button class="checkout-btn">Proceed to Checkout</button></a>
    </div>
  </section>

  <nav class="bottom-nav">
  <a href="index.php"><i class="fas fa-tshirt"></i></a>
  <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
  <a href="reviews.php"><i class="fas fa-star"></i></a>
</nav>


  <script>
    function removeFromCart(id) {
      fetch('remove_from_cart.php?id=' + id)
      .then(res => res.text())
      .then(data => location.reload());
    }

    function updateQuantity(id, change) {
      fetch('update_cart.php?id=' + id + '&change=' + change)
      .then(res => res.text())
      .then(data => location.reload());
    }
  </script>

</body>
</html>
