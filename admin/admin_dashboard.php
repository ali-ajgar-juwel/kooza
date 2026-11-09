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
   IMAGE UPLOAD FUNCTION
========================= */
function uploadImage($inputName) {
    if (!empty($_FILES[$inputName]['name'])) {
        $dir = "../images/";
        $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
        $target = $dir . $fileName;

        move_uploaded_file($_FILES[$inputName]['tmp_name'], $target);

        return "images/" . $fileName; // path for DB
    }
    return NULL;
}

/* =========================
   ADD PRODUCT
========================= */
if (isset($_POST['add_product'])) {

    $name  = $_POST['name'];
    $price = $_POST['price'];

    // blank হলে NULL
    $prev = ($_POST['previous_price'] === '') ? NULL : $_POST['previous_price'];

    $desc = $_POST['description'];

    // upload images
    $image  = uploadImage('image');
    $image1 = uploadImage('image1');
    $image2 = uploadImage('image2');
    $image3 = uploadImage('image3');
    $image4 = uploadImage('image4');
    $image5 = uploadImage('image5');
    $image6 = uploadImage('image6');

    $stmt = $conn->prepare("
        INSERT INTO products 
        (name, price, previous_price, description, image, image1, image2, image3, image4, image5, image6)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
    ");

    $stmt->bind_param(
        "sdissssssss",
        $name, $price, $prev, $desc,
        $image, $image1, $image2, $image3, $image4, $image5, $image6
    );

    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}

/* =========================
   DELETE PRODUCT
========================= */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit;
}

/* =========================
   FETCH PRODUCTS
========================= */
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin – Products</title>

<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.admin-header { position: relative; }

.logout-icon {
  position: absolute;
  right: 15px;
  top: 15px;
  color: #fff;
  font-size: 1.2rem;
}

.admin-actions {
  display: flex;
  gap: 8px;
  margin-top: 8px;
}

.admin-actions a {
  flex: 1;
  text-align: center;
  padding: 6px;
  border-radius: 5px;
  font-size: 0.85rem;
  text-decoration: none;
  color: #fff;
}

.edit-btn { background: #000; }
.delete-btn { background: #b00000; }

.add-form input,
.add-form textarea {
  width: 100%;
  margin-bottom: 8px;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.add-form button {
  width: 100%;
  padding: 8px;
  background: #000;
  color: #fff;
  border: none;
  border-radius: 6px;
}
</style>
</head>

<body>

<!-- HEADER -->
<header class="header admin-header">
  <img src="../images/logo.png" class="logo">

  <br>

  <a href="admin_logout.php" class="logout-icon">
    <i class="fas fa-sign-out-alt"></i>
  </a>
</header>

<section class="products-section">
<h2>Manage Products</h2>

<div class="products">



<!-- EXISTING PRODUCTS -->
<?php while($row = $result->fetch_assoc()): ?>
  <div class="product-card" onclick="window.location='product_detail_admin.php?id=<?php echo $row['id']; ?>'">
    <img src="../<?php echo $row['image']; ?>">

    <div class="product-info">
      <h3><?php echo $row['name']; ?></h3>

      <p>
        <?php if ($row['previous_price']): ?>
          <span style="color:red;text-decoration:line-through;">
            $<?php echo $row['previous_price']; ?>
          </span>
        <?php endif; ?>
        $<?php echo $row['price']; ?>
      </p>

      <!-- ADMIN ACTIONS (Optional: only if you want to keep edit/delete buttons) -->
      <div class="admin-actions">
        <a class="edit-btn" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a class="delete-btn"
           href="?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Delete this product?')">
           Delete
        </a>
      </div>
    </div>
  </div>
<?php endwhile; ?>


</div>
</section>
<!-- ADD PRODUCT CARD -->
<div class="product-card">
  <div class="product-info">
    <h3>Add New Product</h3>

    <form method="POST" enctype="multipart/form-data" class="add-form">

      <input type="text" name="name" placeholder="Product name" required>

      <input type="number" name="price" placeholder="Price" required>

      <input type="number" name="previous_price" placeholder="Previous price (optional)">

      <textarea name="description" placeholder="Product description"></textarea>

      <label>Main Image</label>
      <input type="file" name="image" required>

      <label>Gallery Images</label>
      <input type="file" name="image1">
      <input type="file" name="image2">
      <input type="file" name="image3">
      <input type="file" name="image4">
      <input type="file" name="image5">
      <input type="file" name="image6">

      <button name="add_product">Add Product</button>
    </form>
  </div>
</div>
<nav class="bottom-nav">
  <a href="admin_dashboard.php"><i class="fas fa-tshirt"></i></a>
  <a href="orders.php"><i class="fas fa-box"></i></a>
  <a href="reviews.php"><i class="fas fa-star"></i></a>
</nav>

</body>
</html>
