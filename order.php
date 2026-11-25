<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Database-driven pizza ordering page with live price calculation.
@ASSESSME.ANALYZE: YES
-->

<?php
require_once "dbConn.php";

$pizzas = $pdo->query("SELECT * FROM pizzas WHERE active = 1 ORDER BY name")->fetchAll();
$sizes = $pdo->query("SELECT * FROM sizes ORDER BY id")->fetchAll();
$crusts = $pdo->query("SELECT * FROM crusts ORDER BY id")->fetchAll();
$toppings = $pdo->query("SELECT * FROM toppings ORDER BY name")->fetchAll();
$extras = $pdo->query("SELECT * FROM extras ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order | Roar & Order</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">🍕 Roar & Order</div>
    <nav>
        <a href="index.php">Home</a>
        <a href="order.php">Order</a>
        <a href="login.php">Admin</a>
    </nav>
</header>

<section class="order-page">
    <div class="section-title">
        <p class="tagline dark">BUILD YOUR PIZZA</p>
        <h2>Customize your order</h2>
    </div>

    <form class="order-layout" action="checkout.php" method="POST" id="orderForm">

        <div class="order-panel">

            <label>Choose Pizza</label>
            <select name="pizza_id" id="pizza" required>
                <?php foreach ($pizzas as $pizza): ?>
                    <option 
                        value="<?php echo (int)$pizza['id']; ?>"
                        data-price="<?php echo htmlspecialchars($pizza['base_price_small']); ?>">
                        <?php echo htmlspecialchars($pizza['name']); ?>
                        — €<?php echo number_format($pizza['base_price_small'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Size</label>
            <select name="size_id" id="size" required>
                <?php foreach ($sizes as $size): ?>
                    <option 
                        value="<?php echo (int)$size['id']; ?>"
                        data-price="<?php echo htmlspecialchars($size['multiplier']); ?>">
                        <?php echo htmlspecialchars($size['name']); ?>
                        × <?php echo htmlspecialchars($size['multiplier']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Crust</label>
            <select name="crust_id" id="crust" required>
                <?php foreach ($crusts as $crust): ?>
                    <option 
                        value="<?php echo (int)$crust['id']; ?>"
                        data-price="<?php echo htmlspecialchars($crust['price']); ?>">
                        <?php echo htmlspecialchars($crust['name']); ?>
                        + €<?php echo number_format($crust['price'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Toppings</label>
            <div class="checkbox-grid">
                <?php foreach ($toppings as $topping): ?>
                    <label>
                        <input 
                            type="checkbox" 
                            name="toppings[]" 
                            class="topping" 
                            value="<?php echo (int)$topping['id']; ?>"
                            data-price="<?php echo htmlspecialchars($topping['price']); ?>">
                        <?php echo htmlspecialchars($topping['name']); ?>
                        + €<?php echo number_format($topping['price'], 2); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Extras</label>
            <div class="checkbox-grid">
                <?php foreach ($extras as $extra): ?>
                    <label>
                        <input 
                            type="checkbox" 
                            name="extras[]" 
                            class="extra" 
                            value="<?php echo (int)$extra['id']; ?>"
                            data-price="<?php echo htmlspecialchars($extra['price']); ?>">
                        <?php echo htmlspecialchars($extra['name']); ?>
                        + €<?php echo number_format($extra['price'], 2); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <label>Quantity</label>
            <input type="number" name="quantity" id="quantity" min="1" max="10" value="1" required>

            <label>Order Type</label>
            <select name="order_type" id="orderType" required>
                <option value="pickup">Pickup — Free</option>
                <option value="delivery">Delivery — €2.50</option>
            </select>

        </div>

        <aside class="summary-card">
            <h3>Order Summary</h3>

            <div class="summary-line">
                <span>Item subtotal</span>
                <strong id="itemSubtotal">€0.00</strong>
            </div>

            <div class="summary-line">
                <span>Delivery</span>
                <strong id="deliveryFee">€0.00</strong>
            </div>

            <div class="summary-line">
                <span>Tax 8%</span>
                <strong id="tax">€0.00</strong>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <strong id="grandTotal">€0.00</strong>
            </div>

            <button type="submit" class="btn primary-btn full-btn">
                Continue to Checkout
            </button>
        </aside>

    </form>
</section>

<script src="assets/js/order.js"></script>
</body>
</html>