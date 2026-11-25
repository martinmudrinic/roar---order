-- Users table for authentication
CREATE TABLE `users` (
  `id`       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email`    VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name`     VARCHAR(100) NOT NULL,
  `role`     ENUM('admin', 'customer') NOT NULL DEFAULT 'customer',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pizzas table
CREATE TABLE `pizzas` (
  `id`               INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`             VARCHAR(100) NOT NULL,
  `description`      TEXT,
  `base_price_small` DECIMAL(6,2) NOT NULL,
  `active`           TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sizes table
CREATE TABLE `sizes` (
  `id`    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`  VARCHAR(50)  NOT NULL,
  `multiplier` DECIMAL(3,2) NOT NULL DEFAULT 1.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Crust types table
CREATE TABLE `crusts` (
  `id`    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`  VARCHAR(50)  NOT NULL,
  `price` DECIMAL(6,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Toppings table
CREATE TABLE `toppings` (
  `id`    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`  VARCHAR(50)  NOT NULL,
  `price` DECIMAL(6,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Extras table
CREATE TABLE `extras` (
  `id`    INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name`  VARCHAR(50)  NOT NULL,
  `price` DECIMAL(6,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders table
CREATE TABLE `orders` (
  `id`         INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id`    INT,
  `order_date` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`     ENUM('pending', 'processing', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
  `total`      DECIMAL(8,2) NOT NULL,
  `address`    TEXT         NOT NULL,
  `phone`      VARCHAR(20)  NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order items table
CREATE TABLE `order_items` (
  `id`         INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id`   INT          NOT NULL,
  `pizza_id`   INT          NOT NULL,
  `size_id`    INT          NOT NULL,
  `crust_id`   INT          NOT NULL,
  `quantity`   INT          NOT NULL DEFAULT 1,
  `item_total` DECIMAL(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order item toppings table
CREATE TABLE `order_item_toppings` (
  `id`            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_item_id` INT NOT NULL,
  `topping_id`    INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order item extras table
CREATE TABLE `order_item_extras` (
  `id`            INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_item_id` INT NOT NULL,
  `extra_id`      INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

<?php
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo $hash;
?>


-- Insert admin user (replace the hash with the one you generated)
INSERT INTO `users` (`email`, `password`, `name`, `role`) VALUES
('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

-- Insert sample customers
INSERT INTO `users` (`email`, `password`, `name`, `role`) VALUES
('customer1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'customer'),
('customer2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'customer');

-- Insert pizzas
INSERT INTO `pizzas` (`name`, `description`, `base_price_small`) VALUES
('Margherita', 'Classic pizza with tomato sauce, mozzarella, and fresh basil', 8.99),
('Pepperoni', 'Tomato sauce, mozzarella, and pepperoni', 9.99),
('Hawaiian', 'Tomato sauce, mozzarella, ham, and pineapple', 10.99),
('Vegetarian', 'Tomato sauce, mozzarella, mushrooms, bell peppers, onions, and olives', 10.49),
('Meat Lovers', 'Tomato sauce, mozzarella, pepperoni, ham, bacon, and sausage', 12.99),
('BBQ Chicken', 'BBQ sauce, mozzarella, grilled chicken, red onions, and cilantro', 11.99),
('White Pizza', 'Garlic sauce, mozzarella, ricotta, and spinach', 10.99);

-- Insert sizes
INSERT INTO `sizes` (`name`, `multiplier`) VALUES
('Small', 1.00),
('Medium', 1.25),
('Large', 1.50),
('Extra Large', 1.75);

-- Insert crust types
INSERT INTO `crusts` (`name`, `price`) VALUES
('Classic', 0.00),
('Thin Crust', 1.00),
('Stuffed Crust', 2.50);

-- Insert toppings
INSERT INTO `toppings` (`name`, `price`) VALUES
('Extra Cheese', 1.50),
('Mushrooms', 1.00),
('Pepperoni', 1.50),
('Onions', 0.75),
('Olives', 0.75);

-- Insert extras
INSERT INTO `extras` (`name`, `price`) VALUES
('Garlic Bread', 3.99),
('Cheese Sticks', 4.99),
('Soda', 1.99);

-- Insert sample orders
INSERT INTO `orders` (`user_id`, `status`, `total`, `address`, `phone`) VALUES
(2, 'delivered', 35.97, '123 Main St, Apt 4B, New York, NY 10001', '555-123-4567'),
(3, 'processing', 28.48, '456 Oak Ave, Chicago, IL 60601', '555-987-6543');

-- Insert order items for order 1
INSERT INTO `order_items` (`order_id`, `pizza_id`, `size_id`, `crust_id`, `quantity`, `item_total`) VALUES
(1, 2, 2, 1, 1, 12.49), -- Pepperoni Medium
(1, 4, 3, 3, 1, 18.98), -- Vegetarian Large with Stuffed Crust
(1, 7, 1, 1, 1, 10.99); -- White Pizza Small

-- Insert order items for order 2
INSERT INTO `order_items` (`order_id`, `pizza_id`, `size_id`, `crust_id`, `quantity`, `item_total`) VALUES
(2, 1, 1, 2, 1, 9.99), -- Margherita Small with Thin Crust
(2, 3, 2, 1, 1, 13.74); -- Hawaiian Medium

-- Insert order item toppings for order 1
INSERT INTO `order_item_toppings` (`order_item_id`, `topping_id`) VALUES
(2, 1), -- Extra Cheese on Vegetarian
(2, 3); -- Pepperoni on Vegetarian

-- Insert order item toppings for order 2
INSERT INTO `order_item_toppings` (`order_item_id`, `topping_id`) VALUES
(4, 2), -- Mushrooms on Hawaiian
(4, 4); -- Onions on Hawaiian

-- Insert order item extras for order 1
INSERT INTO `order_item_extras` (`order_item_id`, `extra_id`) VALUES
(1, 1), -- Garlic Bread with Pepperoni
(3, 3); -- Soda with White Pizza