<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Admin page for updating pizza and modifier prices using prepared statements.
@ASSESSME.ANALYZE: YES
-->

<?php
require_once "auth_check.php";
require_once "../dbConn.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $type = $_POST["type"] ?? "";
    $id = (int)($_POST["id"] ?? 0);
    $price = (float)($_POST["price"] ?? 0);

    if ($id > 0 && $price >= 0) {

        if ($type === "pizza") {
            $stmt = $pdo->prepare("UPDATE pizzas SET base_price_small = ? WHERE id = ?");
            $stmt->execute([$price, $id]);
            $message = "Pizza price updated.";
        }

        if ($type === "crust") {
            $stmt = $pdo->prepare("UPDATE crusts SET price = ? WHERE id = ?");
            $stmt->execute([$price, $id]);
            $message = "Crust price updated.";
        }

        if ($type === "topping") {
            $stmt = $pdo->prepare("UPDATE toppings SET price = ? WHERE id = ?");
            $stmt->execute([$price, $id]);
            $message = "Topping price updated.";
        }

        if ($type === "extra") {
            $stmt = $pdo->prepare("UPDATE extras SET price = ? WHERE id = ?");
            $stmt->execute([$price, $id]);
            $message = "Extra price updated.";
        }
    }
}

$pizzas = $pdo->query("SELECT * FROM pizzas ORDER BY name")->fetchAll();
$crusts = $pdo->query("SELECT * FROM crusts ORDER BY name")->fetchAll();
$toppings = $pdo->query("SELECT * FROM toppings ORDER BY name")->fetchAll();
$extras = $pdo->query("SELECT * FROM extras ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Prices | Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">🍕 Admin Panel</div>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="../index.php">Site</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<section class="admin-page">

    <div class="section-title">
        <p class="tagline dark">PRICE CONFIGURATION</p>
        <h2>Update menu prices</h2>
    </div>

    <?php if (!empty($message)): ?>
        <div class="success-box">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="price-section">
        <h3>Pizza Prices</h3>

        <?php foreach ($pizzas as $pizza): ?>
            <form method="POST" class="price-row">
                <input type="hidden" name="type" value="pizza">
                <input type="hidden" name="id" value="<?php echo (int)$pizza["id"]; ?>">

                <span><?php echo htmlspecialchars($pizza["name"]); ?></span>

                <input 
                    type="number" 
                    name="price" 
                    step="0.01" 
                    min="0" 
                    value="<?php echo htmlspecialchars($pizza["base_price_small"]); ?>"
                    required
                >

                <button type="submit">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

    <div class="price-section">
        <h3>Crust Prices</h3>

        <?php foreach ($crusts as $crust): ?>
            <form method="POST" class="price-row">
                <input type="hidden" name="type" value="crust">
                <input type="hidden" name="id" value="<?php echo (int)$crust["id"]; ?>">

                <span><?php echo htmlspecialchars($crust["name"]); ?></span>

                <input 
                    type="number" 
                    name="price" 
                    step="0.01" 
                    min="0" 
                    value="<?php echo htmlspecialchars($crust["price"]); ?>"
                    required
                >

                <button type="submit">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

    <div class="price-section">
        <h3>Topping Prices</h3>

        <?php foreach ($toppings as $topping): ?>
            <form method="POST" class="price-row">
                <input type="hidden" name="type" value="topping">
                <input type="hidden" name="id" value="<?php echo (int)$topping["id"]; ?>">

                <span><?php echo htmlspecialchars($topping["name"]); ?></span>

                <input 
                    type="number" 
                    name="price" 
                    step="0.01" 
                    min="0" 
                    value="<?php echo htmlspecialchars($topping["price"]); ?>"
                    required
                >

                <button type="submit">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

    <div class="price-section">
        <h3>Extra Prices</h3>

        <?php foreach ($extras as $extra): ?>
            <form method="POST" class="price-row">
                <input type="hidden" name="type" value="extra">
                <input type="hidden" name="id" value="<?php echo (int)$extra["id"]; ?>">

                <span><?php echo htmlspecialchars($extra["name"]); ?></span>

                <input 
                    type="number" 
                    name="price" 
                    step="0.01" 
                    min="0" 
                    value="<?php echo htmlspecialchars($extra["price"]); ?>"
                    required
                >

                <button type="submit">Update</button>
            </form>
        <?php endforeach; ?>
    </div>

</section>

</body>
</html>