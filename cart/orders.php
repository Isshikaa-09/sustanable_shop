<?php
session_start();
include '../config/db.php'; // Database connection
require '../vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to continue.'); window.location.href = '../login.php';</script>";
    exit;
}

// Fetch Cart Items
$query = "SELECT p.name, p.price, c.quantity 
          FROM cart c 
          JOIN products p ON c.product_id = p.id 
          WHERE c.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$_SESSION['user_id']]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Checkout Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_checkout'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $pincode = trim($_POST['pincode'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate Required Fields
    if (empty($name) || empty($phone) || empty($address) || empty($pincode) || empty($email)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
        exit;
    }

    // Validate Email Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.');</script>";
        exit;
    }

    // Insert Order into Database
    $orderDetails = "";
    foreach ($cart_items as $item) {
        $orderDetails .= "{$item['name']} - Rs " . number_format($item['price'], 2) . " x {$item['quantity']}<br>";
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, order_details, total_amount, email) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $orderDetails, $total, $email]);

    // Clear the cart after placing the order
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    // Display Animated Popup & Redirect
    echo "<script>
        setTimeout(function() {
            alert('Order placed successfully! Redirecting to homepage...');
            window.location.href = '../index.php';
        }, 1500);
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
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
            --input-border: #E0E0E0;
            --input-focus: #00796B;
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
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        @media (min-width: 768px) {
            main {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .order-summary {
            background-color: var(--card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
            height: fit-content;
        }
        
        .checkout-form {
            background-color: var(--card);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 0.5rem;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid var(--primary-light);
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-name {
            font-weight: 500;
        }
        
        .item-price {
            font-weight: 600;
            color: var(--primary);
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
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-secondary);
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 2px solid var(--input-border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(0, 121, 107, 0.2);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
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
            margin-top: 1rem;
        }
        
        .checkout-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .checkout-btn:active {
            transform: translateY(0);
        }
        
        .back-to-cart {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .back-to-cart:hover {
            color: var(--primary);
        }
        
        .payment-methods {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .payment-method {
            flex: 1;
            padding: 1rem;
            border: 2px solid var(--input-border);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .payment-method.active {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }
        
        .payment-method i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary);
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .order-summary, .checkout-form {
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
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            main {
                grid-template-columns: 1fr;
            }
            
            .order-summary {
                order: 1;
            }
            
            .checkout-form {
                order: 0;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="cart.php" class="nav-button">
                <i class="fas fa-arrow-left"></i> Back to Cart
            </a>
            <h1 class="page-title">Checkout</h1>
            <div style="width: 100px;"></div> <!-- Spacer for alignment -->
        </div>
    </header>

    <main>
        <div class="checkout-form">
            <h2 class="section-title">Shipping Information</h2>
            
            <form method="POST">
                <input type="hidden" name="proceed_checkout" value="1">
                
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
                
                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" required>
                </div>
                
                <div class="form-group">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea id="address" name="address" class="form-control" placeholder="Enter your full address" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="pincode" class="form-label">PIN Code</label>
                    <input type="text" id="pincode" name="pincode" class="form-control" placeholder="Enter your PIN code" required>
                </div>
                
                <h3 class="section-title">Payment Method</h3>
                
                <div class="payment-methods">
                    <div class="payment-method active" onclick="selectPayment(this)">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>Cash on Delivery</div>
                    </div>
            
                </div>
                
                <button type="submit" class="checkout-btn">
                    <i class="fas fa-lock"></i> Place Order
                </button>
                
                <a href="cart.php" class="back-to-cart">
                    <i class="fas fa-arrow-left"></i> Return to Cart
                </a>
            </form>
        </div>
        
        <div class="order-summary">
            <h2 class="section-title">Order Summary</h2>
            
            <?php if ($cart_items): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <div class="item-name">
                            <?php echo htmlspecialchars($item['name']); ?> 
                            <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                        </div>
                        <div class="item-price">Rs <?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                <?php endforeach; ?>
                
                <div class="total-row">
                    <span class="total-label">Total</span>
                    <span class="total-value">Rs <?php echo number_format($total, 2); ?></span>
                </div>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </main>

   
</body>
</html>

