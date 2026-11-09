<?php
session_start();
include '../config.php';

/* =========================
   AUTH CHECK
========================= */
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

/* =========================
   DELETE REVIEW
========================= */
if (isset($_GET['delete'])) {
    $review_id = (int)$_GET['delete'];
    $conn->query("DELETE FROM reviews WHERE id=$review_id");
    header("Location: reviews.php");
    exit;
}

/* =========================
   FETCH PRODUCTS WITH REVIEWS
========================= */
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
<title>Admin – Reviews</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{font-family:'Segoe UI',sans-serif;background:#f5f5f5;margin:0;padding:0;}
.header.admin-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:10px 15px;
  background:#000;
  color:#fff;
  position:sticky;
  top:0;
  z-index:100;
}
.header.admin-header img{height:35px;}
.logout-icon{color:#fff;font-size:1.2rem;text-decoration:none;}
.review-section { max-width:900px; margin:20px auto; padding-bottom:70px; }
.product-review { background: #f4f4f4; padding:20px; border-radius:10px; margin-bottom:20px; display:flex; gap:20px; flex-wrap:wrap; }
.product-review img { width:150px; height:150px; object-fit:cover; border-radius:8px; }
.review-content { flex:1; min-width:200px; }
.review-card { background:#fff; padding:10px; border-radius:5px; margin-bottom:10px; position:relative; }
.review-card strong { display:block; }
.delete-review { position:absolute; top:5px; right:5px; background:#b00000; color:#fff; border:none; border-radius:3px; padding:2px 5px; cursor:pointer; font-size:.75rem; }
.delete-review:hover { background:#ff0000; }
.bottom-nav{display:flex;justify-content:space-around;align-items:center;position:fixed;bottom:0;left:0;width:100%;background:#000;color:#fff;height:50px;z-index:100;}
.bottom-nav a{color:#fff;text-decoration:none;font-size:1.2rem;display:flex;flex-direction:column;align-items:center;}
.bottom-nav a:hover{color:#ccc;}
</style>
</head>
<body>

<header class="header admin-header">
  <img src="../images/logo.png" class="logo">
  <br>
</header>

<section class="review-section">
  <h2>Product Reviews</h2>

  <?php if ($products->num_rows > 0): ?>
    <?php while ($product = $products->fetch_assoc()): ?>
      <div class="product-review">
        <!-- Product Image -->
        <img src="../<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">

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
              <!-- DELETE BUTTON -->
              <a href="?delete=<?php echo $r['id']; ?>" class="delete-review" onclick="return confirm('Delete this review?')">Delete</a>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No reviews available yet.</p>
  <?php endif; ?>

</section>

<!-- Bottom Nav -->
<nav class="bottom-nav">
  <a href="admin_dashboard.php"><i class="fas fa-tshirt"></i></a>
  <a href="orders.php"><i class="fas fa-box"></i></a>
  <a href="reviews.php"><i class="fas fa-star"></i></a>
</nav>

</body>
</html>
