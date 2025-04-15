<?php
include '../config/db.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $query->execute([$id]);
    $product = $query->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #00796B;
            --primary-dark: #004D40;
            --primary-light: #B2DFDB;
            --text-primary: #37474F;
            --text-secondary: #546E7A;
            --background: #f5f7fa;
            --card: #ffffff;
            --accent: #009688;
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
        }
        
        header {
            background-color: var(--primary);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }
        
        .product-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 2rem;
            background-color: var(--card);
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            padding: 2rem;
            margin-top: 2rem;
            overflow: hidden;
        }
        
        @media (min-width: 768px) {
            .product-container {
                grid-template-columns: 1fr 1fr;
            }
        }
        
        .product-image-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .product-image:hover {
            transform: scale(1.03);
        }
        
        .product-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .product-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--text-primary);
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 0.5rem;
        }
        
        .product-description {
            margin-bottom: 1.5rem;
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .product-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .meta-item {
            background-color: var(--primary-light);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .price {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }
        
        .eco-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }
        
        .eco-stars {
            color: #4CAF50;
        }
        
        .add-to-cart-form {
            margin-top: 1rem;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .quantity-label {
            margin-right: 1rem;
            font-weight: 500;
        }
        
        .quantity-input {
            width: 100px;
            padding: 0.75rem;
            border: 2px solid var(--primary);
            border-radius: 8px;
            text-align: center;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .quantity-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(0, 150, 136, 0.2);
        }
        
        .add-to-cart-btn {
            width: 100%;
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
        }
        
        .add-to-cart-btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .add-to-cart-btn:active {
            transform: translateY(0);
        }
        
        .header-content {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .back-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .back-link:hover {
            opacity: 0.8;
        }

        .product-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
/* Rating Stars */
.rating {
            display: flex;
            gap: 2px;
            margin-bottom: 10px;
        }

        .star {
            color: #ffc107;
            font-size: 14px;
        }

        .star-empty {
            color: #e0e0e0;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="../index.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
            <h2>Product Details</h2>
            <div></div> <!-- Empty div for flex spacing -->
        </div>
    </header>

    <div class="container">
        <div class="product-container">
            <div class="product-image-container">
                <img 
                    src="<?php echo file_exists('../' . $product['image']) ? '../' . $product['image'] : '../images/placeholder.png'; ?>" 
                    alt="<?php echo $product['name']; ?>" 
                    class="product-image"
                >
            </div>
            
            <div class="product-details">
                <div>
                    <h1 class="product-title"><?php echo $product['name']; ?></h1>
                    <p class="product-description"><?php echo $product['description']; ?></p>
                    
                    <div class="product-meta">
                        <span class="meta-item">
                            <i class="fas fa-tag"></i> 
                            <?php echo isset($product['category']) ? $product['category'] : 'General'; ?>
                        </span>
                        <?php if(isset($product['stock']) && $product['stock'] > 0): ?>
                            <span class="meta-item">
                                <i class="fas fa-check-circle"></i> In Stock
                            </span>
                        <?php else: ?>
                            <span class="meta-item" style="background-color: #FFCDD2; color: #B71C1C;">
                                <i class="fas fa-times-circle"></i> Out of Stock
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="price">Rs:<?php echo $product['price']; ?></div>
                    
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
                    </div>
                </div>
                
                <form method="POST" action="../cart/cart.php" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="quantity-control">
                        <label for="quantity" class="quantity-label">Quantity:</label>
                        <input 
                            type="number" 
                            id="quantity"
                            name="quantity" 
                            value="1" 
                            min="1" 
                            class="quantity-input"
                        >
                    </div>
                    
                    <button type="submit" class="add-to-cart-btn">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

