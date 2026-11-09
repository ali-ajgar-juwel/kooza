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

// Fetch product
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();
if (!$product) {
    header("Location: admin_dashboard.php");
    exit;
}

// Reuse the same upload function
function uploadImage($inputName, $old=null) {
    if (!empty($_FILES[$inputName]['name'])) {
        $dir = "../images/";
        $fileName = time() . "_" . basename($_FILES[$inputName]['name']);
        $target = $dir . $fileName;
        move_uploaded_file($_FILES[$inputName]['tmp_name'], $target);
        return "images/" . $fileName;
    }
    return $old;
}

// Update product
if (isset($_POST['update_product'])) {
    $name  = $_POST['name'];
    $price = $_POST['price'];
    $prev  = ($_POST['previous_price'] === '') ? NULL : $_POST['previous_price'];
    $desc  = $_POST['description'];

    $image  = uploadImage('image',  $product['image']);
    $image1 = uploadImage('image1', $product['image1']);
    $image2 = uploadImage('image2', $product['image2']);
    $image3 = uploadImage('image3', $product['image3']);
    $image4 = uploadImage('image4', $product['image4']);
    $image5 = uploadImage('image5', $product['image5']);
    $image6 = uploadImage('image6', $product['image6']);

    $stmt = $conn->prepare("
        UPDATE products SET
        name=?, price=?, previous_price=?, description=?,
        image=?, image1=?, image2=?, image3=?, image4=?, image5=?, image6=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "sdissssssssi",
        $name, $price, $prev, $desc,
        $image, $image1, $image2, $image3, $image4, $image5, $image6,
        $id
    );
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Product</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.admin-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:#000;padding:12px;
}
.admin-header img{height:38px}
.back-btn, .logout-btn{color:#fff;font-size:1.2rem;text-decoration:none;}

.form-wrapper{padding:12px;}
.form-box{background:#fff;padding:14px;border-radius:10px;}
.form-box input, .form-box textarea{width:100%;padding:8px;margin-bottom:8px;border-radius:6px;border:1px solid #ccc;}
.form-box textarea{min-height:80px;}
.form-box button{width:100%;padding:10px;background:#000;color:#fff;border:none;border-radius:6px;font-size:1rem;}
.image-preview{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;}
.image-preview img{width:60px;height:60px;object-fit:cover;border-radius:6px;border:1px solid #ddd;}
.label{font-size:.8rem;color:#555;margin-bottom:4px;}
@media(min-width:768px){.form-wrapper{max-width:600px;margin:auto;}}
</style>
</head>
<body>

<!-- HEADER -->
<header class="admin-header">
  <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>
  <img src="../images/logo.png">
  <a href="admin_logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
</header>

<div class="form-wrapper">
<div class="form-box">
<h3>Edit Product</h3>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" value="<?=$product['name']?>" required>
<input type="number" step="0.01" name="price" value="<?=$product['price']?>" required>
<input type="number" step="0.01" name="previous_price" value="<?=$product['previous_price']?>" placeholder="Previous price (optional)">
<textarea name="description"><?=$product['description']?></textarea>

<div class="label">Current Images</div>
<div class="image-preview">
<?php for($i=0;$i<=6;$i++):
  $col = $i==0?'image':"image$i";
  if(!empty($product[$col])): ?>
    <img src="../<?=$product[$col]?>">
<?php endif; endfor; ?>
</div>

<div class="label">Change Images</div>
<input type="file" name="image">
<input type="file" name="image1">
<input type="file" name="image2">
<input type="file" name="image3">
<input type="file" name="image4">
<input type="file" name="image5">
<input type="file" name="image6">

<button name="update_product">Update Product</button>
</form>
</div>
</div>

</body>
</html>
