<?php
include 'config.php';

// Fetch products from database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kooza Clothes Shop</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <!-- Header -->
  <header class="header">
    <img src="images/logo.png" alt="Logo" class="logo">
    <form method="GET" action="index.php">
      <input type="text" name="search" placeholder="Search products..." class="search-box">
    </form>
  </header>

  <!-- Products Section -->
  <section class="products-section" id="products">
    <h2>Products</h2>
    <div class="products">

    <?php
    if (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $sql = "SELECT * FROM products WHERE name LIKE '%$search%'";
        $result = $conn->query($sql);
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Build the Buy URL
            $buyUrl = "confirm.php?id=" . $row['id'];
            ?>
            <div class="product-card" onclick="location.href='product.php?id=<?php echo $row['id']; ?>'">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="product-info">
                  <h3><?php echo $row['name']; ?></h3>
                 <p>
<?php if (!empty($row['previous_price'])): ?>
    <span style="color:red; text-decoration:line-through; margin-right:5px;">
        $<?php echo $row['previous_price']; ?>
    </span>
    <span>
        $<?php echo $row['price']; ?>
    </span>
<?php else: ?>
    $<?php echo $row['price']; ?>
<?php endif; ?>
</p>

                  <div class="product-buttons">
    <!-- Buy Now as POST form -->
    <form method="POST" action="confirm.php" style="display:inline;">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <button type="submit" class="buy-now" onclick="event.stopPropagation();">Buy</button>
    </form>

    <button onclick="event.stopPropagation(); addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
</div>

                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>

    </div>
  </section>

  <!-- Bottom navigation -->
<nav class="bottom-nav">
  <a href="index.php"><i class="fas fa-tshirt"></i></a>
  <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
  <a href="reviews.php"><i class="fas fa-star"></i></a>
</nav>



 <script>
function addToCart(productId) {
  fetch('add_to_cart.php?id=' + productId)
    .then(res => res.text())
    .then(data => {
        showPopup(data); // Show popup with message from PHP
    })
    .catch(err => showPopup('Error adding to cart'));
}

function showPopup(message) {
    const popup = document.getElementById('popup');
    popup.textContent = message; // Set the message
    popup.style.display = 'block'; // Show it
    setTimeout(() => {
        popup.style.display = 'none'; // Hide after 2 seconds
    }, 2000);
}
</script>

<!-- Popup Notification -->
<div id="popup" class="popup">Added to Cart!</div>

</body>
</html>
