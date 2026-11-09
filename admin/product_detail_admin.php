<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = (int)$_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if (!$product) {
    echo "Product not found";
    exit;
}

$admin_name = $_SESSION['admin_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Product - <?=$product['name']?></title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ===== MOBILE-FIRST ===== */
.admin-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:#000;padding:10px;
}
.admin-header img{height:35px;}
.back-btn, .logout-btn{color:#fff;text-decoration:none;font-size:1.2rem;}

.product-page{
  padding:12px;
}

.product-main-img{
  width:100%;
  max-height:300px;
  object-fit:cover;
  border-radius:10px;
  margin-bottom:8px;
}

.product-gallery{
  display:flex;
  overflow-x:auto;
  gap:8px;
  margin-bottom:12px;
}
.product-gallery img{
  width:60px;
  height:60px;
  object-fit:cover;
  border-radius:5px;
  cursor:pointer;
  border:2px solid transparent;
  transition:0.3s;
}
.product-gallery img:hover{
  transform:scale(1.1);
  border-color:#000;
}

.product-details{
  background:#fff;
  padding:12px;
  border-radius:10px;
}
.product-details h2{font-size:1.2rem;margin-bottom:6px;}
.product-details .price{font-weight:bold;margin-bottom:6px;}
.product-details .desc{font-size:.95rem;color:#333;line-height:1.4;}

/* desktop */
@media(min-width:768px){
  .product-main-img{max-height:400px;}
}
</style>
</head>
<body>

<!-- HEADER -->
<header class="admin-header">
  <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
  <img src="../images/logo.png">
  <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
</header>

<section class="product-page">

  <!-- MAIN IMAGE -->
  <?php if(!empty($product['image'])): ?>
    <img src="../<?=$product['image']?>" id="mainImg" class="product-main-img">
  <?php endif; ?>

  <!-- GALLERY -->
  <div class="product-gallery">
    <?php
    // Main image first
    echo '<img src="../'.htmlspecialchars($product['image']).'" onclick="changeImg(this.src)">';
    // Extra images
    for($i=1;$i<=6;$i++){
      $col = "image".$i;
      if(!empty($product[$col])){
        echo '<img src="../'.htmlspecialchars($product[$col]).'" onclick="changeImg(this.src)">';
      }
    }
    ?>
  </div>

  <!-- DETAILS -->
  <div class="product-details">
    <h2><?=htmlspecialchars($product['name'])?></h2>
    <p class="price">
      <?php if(!empty($product['previous_price'])): ?>
        <span style="color:red;text-decoration:line-through;margin-right:5px;">
          $<?=$product['previous_price']?>
        </span>
      <?php endif; ?>
      $<?=$product['price']?>
    </p>
    <p class="desc"><?=htmlspecialchars($product['description'])?></p>
  </div>

</section>

<script>
// Change main image on gallery click
function changeImg(src){
  document.getElementById('mainImg').src = src;
}
</script>

</body>
</html>
