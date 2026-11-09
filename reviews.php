<?php
include 'config.php';

// Fetch products that have at least one review
$sql = "SELECT DISTINCT p.* 
        FROM products p 
        INNER JOIN reviews r ON p.id = r.product_id 
        ORDER BY p.name ASC";
$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product Reviews - Kooza</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.review-section { max-width: 900px; margin: 20px auto; }
.product-review { background: #f4f4f4; padding: 20px; border-radius: 10px; margin-bottom: 20px; display: flex; gap: 20px; }
.product-review img { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; }
.review-content { flex: 1; }
.review-card { background: #fff; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
.review-card strong { display: block; }
.back-btn { display: inline-block; margin-bottom: 20px; background: #000; color: #fff; padding: 8px 15px; border-radius: 5px; text-decoration: none; }
.back-btn:hover { background: #333; }
</style>
</head>
<body>

<header class="header">
  <img src="images/logo.png" class="logo">
</header>

<section class="review-section">

  
  <h2>Product Reviews</h2>

  <?php if ($products->num_rows > 0): ?>
    <?php while ($product = $products->fetch_assoc()): ?>
      <div class="product-review">
        <!-- Product Image -->
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        
        <!-- Reviews -->
        <div class="review-content">
          <h3><?php echo htmlspecialchars($product['name']); ?></h3>
          <?php
          $reviews = $conn->query("SELECT * FROM reviews WHERE product_id=".$product['id']." ORDER BY id DESC");
          while ($r = $reviews->fetch_assoc()):
          ?>
            <div class="review-card">
              <strong><?php echo htmlspecialchars($r['name']); ?></strong>
              <span>⭐ <?php echo $r['rating']; ?>/5</span>
              <p><?php echo htmlspecialchars($r['comment']); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No reviews available yet.</p>
  <?php endif; ?>

</section>
<nav class="bottom-nav">
  <a href="index.php"><i class="fas fa-tshirt"></i></a>
  <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
  <a href="reviews.php"><i class="fas fa-star"></i></a>
</nav>

</body>
</html>
