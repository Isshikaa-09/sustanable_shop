-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 12:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sustainable_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_details` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_details`, `total_amount`, `email`, `status`, `created_at`) VALUES
(1, 1, 'Backpack - Rs 49.99 x 1<br>', 49.99, 'dype472.ec@unishivaji.ac.in', 'Pending', '2025-04-03 10:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) NOT NULL,
  `eco_rating` enum('A','B','C','D','E') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category`, `stock`, `eco_rating`, `created_at`) VALUES
(1, 'Backpack', 'Eco-friendly backpack made from sustainable materials', 49.99, 'images/backpack.jpg', 'Accessories', 50, 'A', '2025-04-03 10:17:22'),
(2, 'beeswax_wraps', 'Reusable beeswax wraps for food storage', 19.99, '/images/beeswax_wraps.jpg', 'Kitchen', 100, 'A', '2025-04-03 10:17:22'),
(3, 'bottle', 'Stainless steel reusable water bottle', 25.99, '/images/bottle.jpg', 'Drinkware', 80, 'A', '2025-04-03 10:17:22'),
(4, 'cleaning_kit', 'Eco-friendly cleaning kit with natural ingredients', 29.99, '/images/cleaning_kit.jpg', 'Household', 60, 'A', '2025-04-03 10:17:22'),
(5, 'food_containers', 'Biodegradable food containers', 22.99, '/images/food_containers.jpg', 'Kitchen', 70, 'A', '2025-04-03 10:17:22'),
(6, 'garden_lights', 'Solar-powered garden lights', 34.99, '/images/garden_lights.jpg', 'Outdoor', 40, 'A', '2025-04-03 10:17:22'),
(7, 'glass_jars', 'Reusable glass jars for storage', 15.99, '/images/glass_jars.jpg', 'Kitchen', 90, 'A', '2025-04-03 10:17:22'),
(8, 'glass_straws', 'Set of reusable glass straws', 12.99, '/images/glass_straws.jpg', 'Drinkware', 120, 'A', '2025-04-03 10:17:22'),
(9, 'hairbrush', 'Wooden hairbrush made from sustainable materials', 14.99, '/images/hairbrush.jpg', 'Personal Care', 80, 'A', '2025-04-03 10:17:22'),
(10, 'makeup_pads', 'Reusable cotton makeup remover pads', 17.99, '/images/makeup_pads.jpg', 'Personal Care', 110, 'A', '2025-04-03 10:17:22'),
(11, 'notebook', 'Recycled paper notebooks', 9.99, '/images/notebook.jpg', 'Stationery', 150, 'A', '2025-04-03 10:17:22'),
(12, 'phone_case', 'Biodegradable phone case', 19.99, '/images/phone_case.jpg', 'Accessories', 60, 'A', '2025-04-03 10:17:22'),
(13, 'powerbank', 'Solar-powered power bank', 39.99, '/images/powerbank.jpg', 'Electronics', 45, 'A', '2025-04-03 10:17:22'),
(14, 'silicone_bags', 'Reusable silicone food storage bags', 24.99, '/images/silicone_bags.jpg', 'Kitchen', 100, 'A', '2025-04-03 10:17:22'),
(15, 'tea_set', 'Eco-friendly ceramic tea set', 49.99, '/images/tea_set.jpg', 'Kitchen', 30, 'A', '2025-04-03 10:17:22'),
(16, 'toothbrush', 'Bamboo toothbrush set', 10.99, '/images/toothbrush.jpg', 'Personal Care', 150, 'A', '2025-04-03 10:17:22'),
(17, 'towels', 'Organic cotton towels', 29.99, '/images/towels.jpg', 'Home', 70, 'A', '2025-04-03 10:17:22'),
(18, 'trash_bags', 'Compostable trash bags', 18.99, '/images/trash_bags.jpg', 'Household', 90, 'A', '2025-04-03 10:17:22'),
(19, 'Tshirt1', 'Organic cotton T-shirt', 22.99, '/images/tshirt1.jpg', 'Clothing', 75, 'A', '2025-04-03 10:17:22'),
(20, 'yogamat', 'Eco-friendly yoga mat made from natural rubber', 59.99, '/images/yogamat.jpg', 'Fitness', 40, 'A', '2025-04-03 10:17:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ayaj', 'ayajmulla2341@gmail.com', '$2y$10$d5zCzZ1UYjr5as/j8Q8CDeHU5kylML0YD5ca2tU/QWWpjIQ6YGLey', '', '2025-04-03 10:09:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
