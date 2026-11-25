<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Admin dashboard for Roar & Order management links.
@ASSESSME.ANALYZE: YES
-->

<?php
require_once "auth_check.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">🍕 Admin Panel</div>

    <nav>
        <a href="../index.php">Site</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<section class="admin-page">
    <div class="section-title">
        <p class="tagline dark">ROAR & ORDER ADMIN</p>
        <h2>Kitchen Control Center</h2>
    </div>

    <div class="admin-grid">
        <a href="#" class="admin-card">
            <h3>Pizza Prices</h3>
            <p>Update menu pizza prices.</p>
        </a>
        <!--@@MILESTONE Admin login and price configuration-->
        <a href="edit_prices.php" class="admin-card">
    <h3>Pizza & Option Prices</h3>
    <p>Update pizza, crust, topping, and extra prices.</p>
</a>

        <a href="#" class="admin-card">
            <h3>Orders</h3>
            <p>View recent customer orders.</p>
        </a>
    </div>
</section>

</body>
</html>