<?php
session_start();
include '../config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to add items to your cart.";
    exit;
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $product_id, $quantity]);
    echo "Product added to cart!";
}

// Display cart items
$query = "SELECT p.name, p.price, c.quantity, p.id as product_id 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Store cart data in session for order processing
$_SESSION['cart_data'] = $cart_items;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #00796B;
            --primary-light: #B2DFDB;
            --primary-dark: #004D40;
            --accent: #FFC107;
            --text-primary: #263238;
            --text-secondary: #546E7A;
            --background: #F5F7FA;
            --card: #FFFFFF;
            --success: #4CAF50;
            --error: #F44336;
            --border-radius: 12px;
            --shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            background-color: var(--primary);
            color: white;
            padding: 1.5rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        
        .header-content {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            text-align: center;
            flex-grow: 1;
        }
        
        .nav-button {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-button:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        main {
            flex: 1;
            width: 90%;
            max-width: 800px;
            margin: 2rem auto;
        }
        
        .cart-container {
            background-color: var(--card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .cart-header {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr;
            padding: 1rem 0;
            border-bottom: 2px solid var(--primary-light);
            font-weight: 600;
            color: var(--text-secondary);
        }
        
        .cart-item {
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 1fr;
            padding: 1.2rem 0;
            border-bottom: 1px solid #eee;
            align-items: center;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .product-name {
            font-weight: 500;
        }
        
        .price, .quantity, .subtotal {
            text-align: center;
        }
        
        .subtotal {
            font-weight: 600;
            color: var(--primary);
        }
        
        .empty-cart {
            text-align: center;
            padding: 3rem 0;
            color: var(--text-secondary);
        }
        
        .empty-cart i {
            font-size: 4rem;
            color: var(--primary-light);
            margin-bottom: 1rem;
        }
        
        .empty-cart p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        
        .summary-container {
            background-color: var(--card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
        }
        
        .summary-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 0.5rem;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        
        .summary-label {
            color: var(--text-secondary);
        }
        
        .summary-value {
            font-weight: 600;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--primary-light);
            font-size: 1.2rem;
        }
        
        .total-label {
            font-weight: 600;
        }
        
        .total-value {
            font-weight: 700;
            color: var(--primary);
        }
        
        .checkout-btn {
            width: 100%;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        
        .checkout-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .checkout-btn:active {
            transform: translateY(0);
        }
        
        .continue-shopping {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .continue-shopping:hover {
            color: var(--primary);
        }
        
        @media (max-width: 768px) {
            .cart-header {
                display: none;
            }
            
            .cart-item {
                grid-template-columns: 1fr;
                gap: 0.5rem;
                padding: 1.5rem 0;
                position: relative;
            }
            
            .product-name {
                font-weight: 600;
                font-size: 1.1rem;
            }
            
            .price, .quantity, .subtotal {
                text-align: left;
                display: flex;
                align-items: center;
            }
            
            .price:before, .quantity:before, .subtotal:before {
                content: attr(data-label);
                font-weight: 500;
                width: 80px;
                color: var(--text-secondary);
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .cart-container, .summary-container {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .cart-item {
            opacity: 0;
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .cart-item:nth-child(1) { animation-delay: 0.1s; }
        .cart-item:nth-child(2) { animation-delay: 0.2s; }
        .cart-item:nth-child(3) { animation-delay: 0.3s; }
        .cart-item:nth-child(4) { animation-delay: 0.4s; }
        .cart-item:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="../index.php" class="nav-button">
                <i class="fas fa-home"></i> Home
            </a>
            <h1 class="page-title">Your Shopping Cart</h1>
            <div style="width: 100px;"></div> <!-- Spacer for alignment -->
        </div>
    </header>

    <main>
        <?php if ($cart_items): ?>
            <div class="cart-container">
                <div class="cart-header">
                    <div>Product</div>
                    <div>Price</div>
                    <div>Quantity</div>
                    <div>Subtotal</div>
                </div>
                
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="price" data-label="Price:">Rs <?php echo number_format($item['price'], 2); ?></div>
                        <div class="quantity" data-label="Quantity:">x<?php echo $item['quantity']; ?></div>
                        <div class="subtotal" data-label="Subtotal:">Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-container">
                <h2 class="summary-title">Order Summary</h2>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rs <?php echo number_format($total, 2); ?></span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value">Free</span>
                </div>
                
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-value">Rs <?php echo number_format($total, 2); ?></span>
                </div>
                
                <form action="orders.php" method="POST">
                    <button type="submit" class="checkout-btn">
                        <i class="fas fa-lock"></i> Proceed to Checkout
                    </button>
                </form>
                
                <a href="../index.php" class="continue-shopping">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
            
        <?php else: ?>
            <div class="cart-container">
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Your cart is empty.</p>
                    <a href="../index.php" class="nav-button" style="margin: 0 auto; background-color: var(--primary);">
                        <i class="fas fa-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
            <script>
                alert("Your cart is empty. Redirecting to homepage...");
                window.location.href = "../index.php";
            </script>
        <?php endif; ?>
    </main>
</body>
</html>

