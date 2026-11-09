<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if (!$product) {
    echo "Product not found";
    exit;
}

// Fetch reviews
$reviews = $conn->query("SELECT * FROM reviews WHERE product_id=$id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($product['name']); ?> - Kooza</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="product.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Basic styling for popup */
.popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #000;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    display: none;
    z-index: 1000;
}

/* Product gallery horizontal scroll */
.product-gallery {
    display: flex;
    overflow-x: auto;
    gap: 10px;
    margin-top: 10px;
    padding-bottom: 5px;
    scrollbar-width: thin; /* Firefox */
}
.product-gallery::-webkit-scrollbar {
    height: 6px;
}
.product-gallery::-webkit-scrollbar-thumb {
    background-color: #ccc;
    border-radius: 3px;
}
.product-gallery img {
    height: 80px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}
.product-gallery img:hover {
    transform: scale(1.1);
    border: 2px solid #000;
}
</style>
</head>
<body>

<header class="header">
  <img src="images/logo.png" class="logo">
</header>

<section class="product-page">

  <!-- Back -->
  <a href="index.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back
  </a>

  <!-- Product Images -->
  <div class="product-images">
    <img src="<?php echo $product['image']; ?>" id="mainImg" class="product-main-img">

    <div class="product-gallery">
      <?php
      // Include main image first
      echo '<img src="'.htmlspecialchars($product['image']).'" onclick="changeImg(this.src)">';
      
      // Loop through extra images (image1..image6)
      for ($i = 1; $i <= 6; $i++) {
        if (!empty($product["image$i"])) {
          echo '<img src="'.htmlspecialchars($product["image$i"]).'" onclick="changeImg(this.src)">';
        }
      }
      ?>
    </div>
  </div>

  <!-- Product Details -->
  <div class="product-details">
    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
    <p class="price">
      <?php if (!empty($product['previous_price'])): ?>
        <span style="color:red; text-decoration:line-through; margin-right:5px;">
          $<?php echo $product['previous_price']; ?>
        </span>
        <span>$<?php echo $product['price']; ?></span>
      <?php else: ?>
        $<?php echo $product['price']; ?>
      <?php endif; ?>
    </p>
    <p class="desc"><?php echo htmlspecialchars($product['description']); ?></p>

    <div class="product-buttons">
    <button onclick="addToCart(<?php echo $id; ?>)">Add to Cart</button>

    <!-- Buy Now button as POST form -->
    <form method="POST" action="confirm.php" style="display:inline;">
        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
        <button type="submit" class="buy-now">Buy Now</button>
    </form>
</div>


  <!-- Reviews -->
  <div class="reviews">
    <h3>Customer Reviews</h3>

    <?php if ($reviews->num_rows > 0): ?>
      <?php while ($r = $reviews->fetch_assoc()): ?>
        <div class="review-card">
          <strong><?php echo htmlspecialchars($r['name']); ?></strong>
          <span>⭐ <?php echo $r['rating']; ?>/5</span>
          <p><?php echo htmlspecialchars($r['comment']); ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reviews yet.</p>
    <?php endif; ?>

    <!-- Review Form -->
    <form class="review-form" method="POST" action="add_review.php">
      <input type="hidden" name="product_id" value="<?php echo $id; ?>">
      <input type="text" name="name" placeholder="Your Name" required>
      <select name="rating" required>
        <option value="">Rating</option>
        <option value="5">5</option>
        <option value="4">4</option>
        <option value="3">3</option>
        <option value="2">2</option>
        <option value="1">1</option>
      </select>
      <textarea name="comment" placeholder="Write your review" required></textarea>
      <button type="submit">Submit Review</button>
    </form>
  </div>

</section>

<!-- Popup -->
<div id="popup" class="popup">Added to Cart</div>

<script>
// Change main image
function changeImg(src){
  document.getElementById('mainImg').src = src;
}

// Add to cart function with popup
function addToCart(id){
  fetch('add_to_cart.php?id=' + id)
    .then(res => res.text())
    .then(msg => {
      const popup = document.getElementById('popup');
      popup.innerText = msg;
      popup.style.display = 'block';
      setTimeout(() => popup.style.display = 'none', 2000);
    })
    .catch(() => {
      const popup = document.getElementById('popup');
      popup.innerText = "Error adding to cart";
      popup.style.display = 'block';
      setTimeout(() => popup.style.display = 'none', 2000);
    });
}
</script>

</body>
</html>
