<?php
session_start();
include '../config.php'; // Database connection

// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit;
}

// Handle login
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch admin from database
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $admin['name'];
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $message = 'Invalid password';
        }
    } else {
        $message = 'Admin not found';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - Kooza</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body setup */
body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Header */
.header {
    width: 100%;
    background-color: #000;
    padding: 15px 0;
    text-align: center;
}
.header .logo {
    max-width: 180px;
}

/* Admin login card */
.admin-login {
    width: 100%;
    max-width: 400px;
    background-color: #fff;
    margin-top: 60px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.admin-login h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.admin-login form {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.admin-login input {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 15px;
}

.admin-login button {
    padding: 12px;
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
.admin-login button:hover {
    background-color: #333;
}

.error-msg {
    color: red;
    text-align: center;
    margin-bottom: 15px;
}

/* Responsive */
@media(max-width: 480px){
    .admin-login {
        margin-top: 40px;
        padding: 20px;
    }
}
</style>
</head>
<body>

<header class="header">
    <img src="../images/logo.png" alt="Logo" class="logo">
</header>

<section class="admin-login">
    <h2>Admin Login</h2>
    <?php if($message): ?>
        <p class="error-msg"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
</section>

</body>
</html>
