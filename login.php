<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Admin login page using PHP sessions and password verification.
@ASSESSME.ANALYZE: YES
-->

<?php
session_start();

$error = "";

$adminEmail = "admin@example.com";
$adminHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === $adminEmail && password_verify($password, $adminHash)) {
        $_SESSION["admin_id"] = 1;
        $_SESSION["admin_email"] = $email;

        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid admin email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">🍕 Roar & Order</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="order.php">Order</a>
    </nav>
</header>

<section class="login-page">
    <form class="login-card" method="POST" action="login.php">
        <p class="tagline dark">ADMIN AREA</p>
        <h1>Staff Login</h1>

        <?php if (!empty($error)): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn primary-btn full-btn">
            Login
        </button>

        <p class="login-hint">
            Test login: admin@example.com / admin123
        </p>
    </form>
</section>

</body>
</html>