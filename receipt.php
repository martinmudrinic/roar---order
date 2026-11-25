<!-- 
@ASSESSME.USERID: YOUR_USERID
@ASSESSME.AUTHOR: YOUR_FULL_NAME
@ASSESSME.DESCRIPTION: Saves database-driven pizza orders, toppings, extras, and displays receipt.
@ASSESSME.ANALYZE: YES
-->

<?php
require_once "dbConn.php";

$customerName = trim($_POST["customer_name"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$email = trim($_POST["email"] ?? "");
$address = trim($_POST["address"] ?? "");

$pizzaId = (int)($_POST["pizza_id"] ?? 0);
$sizeId = (int)($_POST["size_id"] ?? 0);
$crustId = (int)($_POST["crust_id"] ?? 0);
$quantity = (int)($_POST["quantity"] ?? 0);
$orderType = $_POST["order_type"] ?? "";

$toppingIds = $_POST["toppings"] ?? [];
$extraIds = $_POST["extras"] ?? [];

$errors = [];

if ($customerName === "") {
    $errors[] = "Customer name is required.";
}

if ($phone === "") {
    $errors[] = "Phone number is required.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}

if ($orderType !== "pickup" && $orderType !== "delivery") {
    $errors[] = "Invalid order type.";
}

if ($orderType === "delivery" && $address === "") {
    $errors[] = "Delivery address is required.";
}

if ($quantity < 1 || $quantity > 10) {
    $errors[] = "Invalid quantity.";
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

    $stmt = $pdo->prepare("SELECT * FROM sizes WHERE id = ?");
    $stmt->execute([$sizeId]);
    $size = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT * FROM crusts WHERE id = ?");
    $stmt->execute([$crustId]);
    $crust = $stmt->fetch();

    if (!$pizza || !$size || !$crust) {
        $errors[] = "One or more selected pizza options are invalid.";
    }
}

if (empty($errors)) {
    foreach ($toppingIds as $toppingId) {
        $toppingId = (int)$toppingId;

        $stmt = $pdo->prepare("SELECT * FROM toppings WHERE id = ?");
        $stmt->execute([$toppingId]);
        $topping = $stmt->fetch();

        if ($topping) {
            $selectedToppings[] = $topping;
        }
    }

    foreach ($extraIds as $extraId) {
        $extraId = (int)$extraId;

        $stmt = $pdo->prepare("SELECT * FROM extras WHERE id = ?");
        $stmt->execute([$extraId]);
        $extra = $stmt->fetch();

        if ($extra) {
            $selectedExtras[] = $extra;
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

    try {
        $pdo->beginTransaction();

        $finalAddress = $orderType === "delivery" ? $address : "Pickup";

        $orderStmt = $pdo->prepare(
            "INSERT INTO orders (status, total, address, phone)
             VALUES (?, ?, ?, ?)"
        );

        $orderStmt->execute([
            "pending",
            $grandTotal,
            $finalAddress,
            $phone
        ]);

        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare(
            "INSERT INTO order_items 
            (order_id, pizza_id, size_id, crust_id, quantity, item_total)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $itemStmt->execute([
            $orderId,
            $pizzaId,
            $sizeId,
            $crustId,
            $quantity,
            $itemSubtotal
        ]);

        $orderItemId = $pdo->lastInsertId();

        foreach ($selectedToppings as $topping) {
            $topStmt = $pdo->prepare(
                "INSERT INTO order_item_toppings (order_item_id, topping_id)
                 VALUES (?, ?)"
            );

            $topStmt->execute([
                $orderItemId,
                $topping["id"]
            ]);
        }

        foreach ($selectedExtras as $extra) {
            $extraStmt = $pdo->prepare(
                "INSERT INTO order_item_extras (order_item_id, extra_id)
                 VALUES (?, ?)"
            );

            $extraStmt->execute([
                $orderItemId,
                $extra["id"]
            ]);
        }

        $pdo->commit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        $errors[] = "Order could not be saved. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt | Roar & Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">🍕 Roar & Order</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="order.php">Order Again</a>
    </nav>
</header>

<section class="receipt-page">

    <?php if (!empty($errors)): ?>

        <div class="receipt-card">
            <h2>Order Error</h2>

            <ul class="error-list">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>

            <a href="order.php" class="btn primary-btn">Back to Order</a>
        </div>

    <?php else: ?>

        <div class="receipt-card success-animation">

            <div class="success-icon">✅</div>

            <p class="tagline dark">ORDER CONFIRMED</p>
            <h1>Thanks for your order!</h1>

            <p class="receipt-message">
                Your pizza is officially in the oven.
            </p>

            <div class="receipt-info">

                <div class="receipt-row">
                    <span>Order Number</span>
                    <strong>#<?php echo htmlspecialchars($orderId); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Customer</span>
                    <strong><?php echo htmlspecialchars($customerName); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Phone</span>
                    <strong><?php echo htmlspecialchars($phone); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Email</span>
                    <strong><?php echo htmlspecialchars($email); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Order Type</span>
                    <strong><?php echo ucfirst(htmlspecialchars($orderType)); ?></strong>
                </div>

                <?php if ($orderType === "delivery"): ?>
                    <div class="receipt-row">
                        <span>Address</span>
                        <strong><?php echo htmlspecialchars($address); ?></strong>
                    </div>
                <?php endif; ?>

            </div>

            <div class="receipt-summary">

                <h3>Your Order</h3>

                <div class="receipt-row">
                    <span>Pizza</span>
                    <strong><?php echo htmlspecialchars($pizza["name"]); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Size</span>
                    <strong><?php echo htmlspecialchars($size["name"]); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Crust</span>
                    <strong><?php echo htmlspecialchars($crust["name"]); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Quantity</span>
                    <strong><?php echo (int)$quantity; ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Toppings</span>
                    <strong>
                        <?php
                        echo empty($selectedToppings)
                            ? "None"
                            : htmlspecialchars(implode(", ", array_column($selectedToppings, "name")));
                        ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Extras</span>
                    <strong>
                        <?php
                        echo empty($selectedExtras)
                            ? "None"
                            : htmlspecialchars(implode(", ", array_column($selectedExtras, "name")));
                        ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Subtotal</span>
                    <strong>€<?php echo number_format($itemSubtotal, 2); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Delivery</span>
                    <strong>€<?php echo number_format($deliveryFee, 2); ?></strong>
                </div>

                <div class="receipt-row">
                    <span>Tax</span>
                    <strong>€<?php echo number_format($tax, 2); ?></strong>
                </div>

                <div class="receipt-total">
                    <span>Total Paid</span>
                    <strong>€<?php echo number_format($grandTotal, 2); ?></strong>
                </div>

            </div>

            <a href="index.php" class="btn primary-btn">Back to Home</a>

        </div>

    <?php endif; ?>

</section>

</body>
</html>