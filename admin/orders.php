<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

// UPDATE ORDER STATUS
if(isset($_POST['update_status'])){
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'] === 'Confirmed' ? 'Confirmed' : 'Pending';
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    header("Location: orders.php");
    exit;
}

// FETCH ORDERS
$orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Orders</title>
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
.orders-section{padding:10px;margin-bottom:60px;}
.order-card{background:#fff;border-radius:8px;padding:10px;margin-bottom:10px;box-shadow:0 1px 4px rgba(0,0,0,0.1);}
.order-card h3{margin:0;font-size:1rem;}
.order-card p{margin:2px 0;font-size:.9rem;}
.status-form{display:flex;align-items:center;gap:5px;margin-top:5px;}
.status-form select{padding:4px;border-radius:5px;border:1px solid #ccc;font-size:.9rem;}
.status-form button{padding:4px 8px;border:none;border-radius:5px;background:#000;color:#fff;font-size:.8rem;cursor:pointer;}
.status-form button:hover{background:#333;}
.order-products{display:flex;flex-wrap:wrap;gap:10px;margin-top:5px;}
.order-products div{text-align:center;width:70px;}
.order-products img{width:60px;height:60px;object-fit:cover;border-radius:5px;}
.bottom-nav{display:flex;justify-content:space-around;align-items:center;position:fixed;bottom:0;left:0;width:100%;background:#000;color:#fff;height:50px;}
.bottom-nav a{color:#fff;text-decoration:none;font-size:1.2rem;display:flex;flex-direction:column;align-items:center;}
.bottom-nav a:hover{color:#ccc;}
@media(max-width:600px){.order-card p,.order-card h3{font-size:.8rem;}}
</style>
</head>
<body>

<header class="header admin-header">
  <img src="../images/logo.png" class="logo">
<br>
</header>

<section class="orders-section">
<h2>Orders</h2>

<?php if($orders->num_rows>0): ?>
  <?php while($order=$orders->fetch_assoc()): ?>
    <div class="order-card">
      <h3>Order #<?php echo $order['id']; ?> - <?php echo htmlspecialchars($order['name']); ?></h3>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
      <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
      <p><strong>Payment:</strong> <?php echo htmlspecialchars($order['payment']); ?></p>
      <p><strong>Total:</strong> $<?php echo $order['total']; ?></p>
      <p><strong>Ordered At:</strong> <?php echo $order['created_at']; ?></p>
      <p><strong>Status:</strong> <?php echo $order['status']; ?></p>

      <!-- PRODUCTS IN ORDER -->
      <div class="order-products">
      <?php
      $items = json_decode($order['items'], true);
      if($items){
          foreach($items as $prod_id => $prod){
              echo '<div>';
              echo '<img src="../'.htmlspecialchars($prod['image']).'" title="'.htmlspecialchars($prod['name']).'">';
              echo '<p style="font-size:.7rem;">'.htmlspecialchars($prod['name']).' x'.$prod['quantity'].'</p>';
              echo '</div>';
          }
      } else {
          echo '<p>No products found</p>';
      }
      ?>
      </div>

      <!-- STATUS UPDATE -->
      <form method="POST" class="status-form">
        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
        <select name="status">
          <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
          <option value="Confirmed" <?php if($order['status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
        </select>
        <button type="submit" name="update_status">Update</button>
      </form>
    </div>
  <?php endwhile; ?>
<?php else: ?>
  <p>No orders yet.</p>
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
