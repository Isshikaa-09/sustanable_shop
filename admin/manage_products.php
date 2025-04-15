<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "Access denied.";
    exit;
}

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category, stock, eco_rating) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['description'], $_POST['price'], $_POST['image'], $_POST['category'], $_POST['stock'], $_POST['eco_rating']]);
    }
}

// Fetch products
$query = "SELECT * FROM products";
$products = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Products</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h1>Manage Products</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" name="price" placeholder="Price" required>
        <input type="text" name="image" placeholder="Image URL">
        <input type="text" name="category" placeholder="Category">
        <input type="number" name="stock" placeholder="Stock" required>
        <select name="eco_rating">
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
            <option value="E">E</option>
        </select>
        <button type="submit" name="add_product">Add Product</button>
    </form>
    <h2>Existing Products</h2>
    <ul>
        <?php foreach ($products as $product): ?>
            <li><?php echo $product['name']; ?> - $<?php echo $product['price']; ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>