<!-- 
@ASSESSME.USERID: mm2945
@ASSESSME.AUTHOR: Martin Mudrinic
@ASSESSME.DESCRIPTION: Main homepage layout for Roar & Order pizza ordering system.
@ASSESSME.ANALYZE: YES
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roar & Order</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<script src="assets/js/app.js"></script>
<body>

<header class="navbar">
    <div class="logo">🍕 Roar & Order</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="order.php">Order</a>
        <a href="login.php">Admin</a>
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <p class="tagline">RIT CAMPUS PIZZA</p>
        <h1>Hot pizza. Fast campus delivery.</h1>
        <p class="hero-text">
            Build your perfect pizza with fresh toppings, crispy crusts, and bold RIT flavor.
            Pickup or delivery — your roar, your order.
        </p>

        <div class="hero-actions">
            <a href="order.php" class="btn primary-btn">Start Your Order</a>
            <a href="#menu" class="btn secondary-btn">View Menu</a>
        </div>
    </div>

    <div class="hero-card">
        <div class="floating-pizza">🍕</div>
        <h3>Today’s Favorite</h3>
        <p>BBQ Chicken with stuffed crust</p>
        <strong>€12.99</strong>
    </div>
</section>

<section class="stats-strip">
    <div>
        <strong>7+</strong>
        <span>Signature Pizzas</span>
    </div>

    <div>
        <strong>4</strong>
        <span>Pizza Sizes</span>
    </div>

    <div>
        <strong>20 min</strong>
        <span>Average Pickup</span>
    </div>
</section>

<section class="menu-preview" id="menu">
    <div class="section-title">
        <p class="tagline dark">POPULAR PICKS</p>
        <h2>Campus favorites</h2>
    </div>

    <div class="pizza-grid">
        <div class="pizza-card">
            <div class="pizza-image">🍅</div>
            <h3>Margherita</h3>
            <p>Tomato sauce, mozzarella, fresh basil, and olive oil.</p>
            <div class="card-bottom">
                <span>€8.99</span>
                <a href="order.php" class="mini-btn">Add</a>
            </div>
        </div>

        <div class="pizza-card featured">
            <div class="badge">Popular</div>
            <div class="pizza-image">🍕</div>
            <h3>Pepperoni</h3>
            <p>Classic pepperoni, mozzarella, and rich house tomato sauce.</p>
            <div class="card-bottom">
                <span>€9.99</span>
                <a href="order.php" class="mini-btn">Add</a>
            </div>
        </div>

        <div class="pizza-card">
            <div class="pizza-image">🔥</div>
            <h3>BBQ Chicken</h3>
            <p>BBQ sauce, grilled chicken, red onions, and melted cheese.</p>
            <div class="card-bottom">
                <span>€11.99</span>
                <a href="order.php" class="mini-btn">Add</a>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <h2>Ready to feed your roar?</h2>
    <p>Customize your pizza, choose pickup or delivery, and checkout in minutes.</p>
    <a href="order.php" class="btn primary-btn">Build My Pizza</a>
</section>

<footer class="site-footer">
    <p>© 2026 Roar & Order — Built for RIT students</p>
</footer>

</body>
</html>