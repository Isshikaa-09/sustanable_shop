<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit;
}

include '../config/db.php';
$query = "SELECT * FROM products";
$products = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="container header-content">
            <a href="#" class="logo">ProductHub</a>
            
            <!-- Added Navigation Menu -->
            <!-- <ul class="nav-menu">
                <li class="nav-item"><a href="#" class="nav-link active">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Shop</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Categories</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Deals</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Contact</a></li>
            </ul> -->
            
            <div class="user-nav">
                <div class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</div>
                <a href="../cart/cart.php" class="cart-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 18c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm10 0c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zM7 16h10v-2h-10v2zm-4-4h18v-2h-18v2z"></path>
                    </svg>
                    Cart
                </a>
                <a href="../auth/logout.php" class="logout-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 13v-2H7V8l-5 4 5 4v-3z"></path>
                        <path d="M20 3h-9c-1.103 0-2 .897-2 2v4h2V5h9v14h-9v-4H9v4c0 1.103.897 2 2 2h9c1.103 0 2-.897 2-2V5c0-1.103-.897-2-2-2z"></path>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
        
    </header>

    <main class="main-content">
        <div class="container">
            <h1 class="page-title">Our Products</h1>
        
            
            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M19 5h-14l1.5-2h11zM21.794 5.392l-2.994-3.992c-.196-.261-.494-.39-.8-.39h-12c-.306 0-.604.129-.8.39l-2.994 3.992c-.175.233-.206.528-.083.791.122.263.372.429.663.429h18.428c.291 0 .541-.166.663-.429.123-.263.092-.558-.083-.791zm-9.794 15.608c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6zm0-10c-2.206 0-4 1.794-4 4s1.794 4 4 4 4-1.794 4-4-1.794-4-4-4zm0 6c-1.103 0-2-.897-2-2s.897-2 2-2 2 .897 2 2-.897 2-2 2z"></path>
                    </svg>
                    <h2>No products found</h2>
                    <p>There are no products available at the moment. Please check back later or browse other categories.</p>
                    <button class="view-details">Explore Categories</button>
                </div>
            <?php else: ?>
                <div class="product-list">
                    <?php foreach ($products as $index => $product): ?>
                        <div class="product-item">
                            <?php if ($index % 5 === 0): ?>
                                <div class="badge badge-sale">SALE</div>
                            <?php elseif ($index % 7 === 0): ?>
                                <div class="badge badge-new">NEW</div>
                            <?php endif; ?>
                            
                            <div class="product-image-container">
                                <?php 
                                $imagePath = strpos($product['image'], 'images/') === false 
                                    ? "../images/" . htmlspecialchars($product['image']) 
                                    : "../" . htmlspecialchars($product['image']);
                                ?>
                                <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                                
                                <button class="wishlist-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="product-content">
                                <!-- Added Rating Stars -->
                                <div class="rating">
                                    <?php 
                                    $rating = mt_rand(3, 5); // Random rating between 3-5 for demo
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <span class="star <?php echo $i <= $rating ? '' : 'star-empty'; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                
                                <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                                <div class="product-price">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm1 14.915V18h-2v-1.08c-2.339-.367-3-2.002-3-2.92h2c.011.143.159 1 2 1 1.38 0 2-.585 2-1 0-.324 0-1-2-1-3.48 0-4-1.88-4-3 0-1.288 1.029-2.584 3-2.915V6.012h2v1.109c1.734.41 2.4 1.853 2.4 2.879h-1.993c-.045-.322-.337-1-1.407-1-1.38 0-2 .676-2 1 0 .374 0 1 2 1 3.227 0 4 1.986 4 3 0 1.307-.675 2.622-3 2.915z"></path>
                                    </svg>
                                    Rs <?php echo htmlspecialchars($product['price']); ?>
                                </div>
                                
                                <div class="product-actions">
                                    <a href="./product_details.php?id=<?php echo $product['id']; ?>" class="view-details">View Details</a>
                                    <!-- <button class="add-to-cart">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z"/>
                                        </svg>
                                    </button> -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="container footer-content">
            <div class="footer-text">&copy; <?php echo date('Y'); ?> ProductHub. All rights reserved.</div>
            <div class="footer-text">Designed with ❤️ for our customers</div>
        </div>
    </footer>
</body>
</html>