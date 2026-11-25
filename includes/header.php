<!-- 
@ASSESSME.USERID: mm2945
@ASSESSME.AUTHOR: Martin Mudrinic
@ASSESSME.DESCRIPTION: Reusable page header and navigation for Roar & Order.
@ASSESSME.ANALYZE: YES
-->
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a href="index.php" class="logo">🍕 Roar & Order</a>

        <ul class="nav-links">
            <li><a href="index.php">Menu</a></li>
            <li><a href="order.php">Order</a></li>

            <?php if (isset($_SESSION['admin_id'])): ?>
                <li><a href="admin/dashboard.php">Admin</a></li>
                <li><a href="admin/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Admin Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main class="page-wrapper"></main>