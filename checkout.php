<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Validates database-driven pizza order data and displays checkout summary.
@ASSESSME.ANALYZE: YES
-->

<?php
require_once "dbConn.php";

$errors = [];

$pizzaId = (int)($_POST["pizza_id"] ?? 0);
$sizeId = (int)($_POST["size_id"] ?? 0);
$crustId = (int)($_POST["crust_id"] ?? 0);
$quantity = (int)($_POST["quantity"] ?? 0);
$orderType = $_POST["order_type"] ?? "";

$toppingIds = $_POST["toppings"] ?? [];
$extraIds = $_POST["extras"] ?? [];

if ($pizzaId <= 0) {
    $errors[] = "Please choose a valid pizza.";
}

if ($sizeId <= 0) {
    $errors[] = "Please choose a valid size.";
}

if ($crustId <= 0) {
    $errors[] = "Please choose a valid crust.";
}

if ($quantity < 1 || $quantity > 10) {
    $errors[] = "Quantity must be between 1 and 10.";
}

if ($orderType !== "pickup" && $orderType !== "delivery") {
    $errors[] = "Please choose pickup or delivery.";
}

$pizza = null;
$size = null;
$crust = null;
$selectedToppings = [];
$selectedExtras = [];

if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT * FROM pizzas WHERE id = ? AND active = 1");
    $stmt->execute([$pizzaId]);
    $pizza = $stmt->fetch();

    if (!$pizza) {
        $errors[] = "Selected pizza was not found.";
    }

    $stmt = $pdo->prepare("SELECT * FROM sizes WHERE id = ?");
    $stmt->execute([$sizeId]);
    $size = $stmt->fetch();

    if (!$size) {
        $errors[] = "Selected size was not found.";
    }

    $stmt = $pdo->prepare("SELECT * FROM crusts WHERE id = ?");
    $stmt->execute([$crustId]);
    $crust = $stmt->fetch();

    if (!$crust) {
        $errors[] = "Selected crust was not found.";
    }
}

if (empty($errors)) {
    foreach ($toppingIds as $toppingId) {
        $toppingId = (int)$toppingId;

        if ($toppingId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM toppings WHERE id = ?");
            $stmt->execute([$toppingId]);
            $topping = $stmt->fetch();

            if ($topping) {
                $selectedToppings[] = $topping;
            }
        }
    }

    foreach ($extraIds as $extraId) {
        $extraId = (int)$extraId;

        if ($extraId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM extras WHERE id = ?");
            $stmt->execute([$extraId]);
            $extra = $stmt->fetch();

            if ($extra) {
                $selectedExtras[] = $extra;
            }
        }
    }

    $toppingsTotal = 0;
    foreach ($selectedToppings as $topping) {
        $toppingsTotal += (float)$topping["price"];
    }

    $extrasTotal = 0;
    foreach ($selectedExtras as $extra) {
        $extrasTotal += (float)$extra["price"];
    }

    $base = (float)$pizza["base_price_small"];
    $sizeMultiplier = (float)$size["multiplier"];
    $crustPrice = (float)$crust["price"];

    $itemSubtotal = (($base * $sizeMultiplier) + $crustPrice + $toppingsTotal + $extrasTotal) * $quantity;
    $deliveryFee = $orderType === "delivery" ? 2.50 : 0;
    $tax = $itemSubtotal * 0.08;
    $grandTotal = $itemSubtotal + $deliveryFee + $tax;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <p class="tagline dark">CHECKOUT</p>
        <h2>Review your order</h2>
    </div>

    <?php if (!empty($errors)): ?>

        <div class="order-panel">
            <h3>Please fix these problems:</h3>

            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>

            <a href="order.php" class="btn primary-btn">Back to Order</a>
        </div>

    <?php else: ?>

        <form action="receipt.php" method="POST" class="checkout-grid">

            <div class="order-panel">
                <h3>Customer Details</h3>

                <label>Your Name</label>
                <input type="text" name="customer_name" required>

                <label>Phone</label>
                <input type="text" name="phone" required>

                <label>Email</label>
                <input type="email" name="email" required>

                <?php if ($orderType === "delivery"): ?>
                    <label>Delivery Address</label>
                    <input type="text" name="address" required>
                <?php endif; ?>

                <input type="hidden" name="pizza_id" value="<?php echo (int)$pizza["id"]; ?>">
                <input type="hidden" name="size_id" value="<?php echo (int)$size["id"]; ?>">
                <input type="hidden" name="crust_id" value="<?php echo (int)$crust["id"]; ?>">
                <input type="hidden" name="quantity" value="<?php echo (int)$quantity; ?>">
                <input type="hidden" name="order_type" value="<?php echo htmlspecialchars($orderType); ?>">

                <?php foreach ($selectedToppings as $topping): ?>
                    <input type="hidden" name="toppings[]" value="<?php echo (int)$topping["id"]; ?>">
                <?php endforeach; ?>

                <?php foreach ($selectedExtras as $extra): ?>
                    <input type="hidden" name="extras[]" value="<?php echo (int)$extra["id"]; ?>">
                <?php endforeach; ?>

                <button type="submit" class="btn primary-btn full-btn">
                    Place Order
                </button>
            </div>

            <aside class="summary-card">
                <h3>Your Pizza</h3>

                <div class="summary-line">
                    <span>Pizza</span>
                    <strong><?php echo htmlspecialchars($pizza["name"]); ?></strong>
                </div>

                <div class="summary-line">
                    <span>Size</span>
                    <strong><?php echo htmlspecialchars($size["name"]); ?></strong>
                </div>

                <div class="summary-line">
                    <span>Crust</span>
                    <strong><?php echo htmlspecialchars($crust["name"]); ?></strong>
                </div>

                <div class="summary-line">
                    <span>Quantity</span>
                    <strong><?php echo (int)$quantity; ?></strong>
                </div>

                <div class="summary-line">
                    <span>Toppings</span>
                    <strong>
                        <?php
                        if (empty($selectedToppings)) {
                            echo "None";
                        } else {
                            echo htmlspecialchars(implode(", ", array_column($selectedToppings, "name")));
                        }
                        ?>
                    </strong>
                </div>

                <div class="summary-line">
                    <span>Extras</span>
                    <strong>
                        <?php
                        if (empty($selectedExtras)) {
                            echo "None";
                        } else {
                            echo htmlspecialchars(implode(", ", array_column($selectedExtras, "name")));
                        }
                        ?>
                    </strong>
                </div>

                <div class="summary-line">
                    <span>Subtotal</span>
                    <strong>€<?php echo number_format($itemSubtotal, 2); ?></strong>
                </div>

                <div class="summary-line">
                    <span>Delivery</span>
                    <strong>€<?php echo number_format($deliveryFee, 2); ?></strong>
                </div>

                <div class="summary-line">
                    <span>Tax</span>
                    <strong>€<?php echo number_format($tax, 2); ?></strong>
                </div>

                <div class="summary-total">
                    <span>Total</span>
                    <strong>€<?php echo number_format($grandTotal, 2); ?></strong>
                </div>
            </aside>

        </form>

    <?php endif; ?>

</section>

</body>
</html>