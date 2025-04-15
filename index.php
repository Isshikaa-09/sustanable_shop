<?php
session_start();
include './config/db.php';

// Redirect logged-in users directly to the product list
if (isset($_SESSION['user_id'])) {
    header("Location: products/product_list.php");
    exit;
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: products/product_list.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}

// Handle signup
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->execute([$name, $email, $password]);
    
    $_SESSION['user_id'] = $conn->lastInsertId();
    $_SESSION['user_name'] = $name;
    header("Location: products/product_list.php");
    exit;
}

// Default active tab
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'login';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Sustainable Shop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #e8eaff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .login-container {
            background-color: white;
            width: 400px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px;
        }
        
        .login-title {
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 25px;
            color: #222;
        }
        
        .tab-navigation {
            display: flex;
            margin-bottom: 25px;
            border-radius: 50px;
            overflow: hidden;
            border: 1px solid #eee;
        }
        
        .tab-btn {
            flex: 1;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            background: none;
        }
        
        .tab-btn.active {
            background-color: #1a56db;
            color: white;
        }
        
        .tab-btn:not(.active) {
            background-color: #f8f9fa;
            color: #333;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-control {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 50px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            border-color: #1a56db;
        }
        
        .forgot-password {
            display: block;
            text-align: left;
            margin-top: -10px;
            margin-bottom: 20px;
            color: #1a56db;
            text-decoration: none;
            font-size: 14px;
        }
        
        .submit-btn {
            width: 100%;
            padding: 15px;
            background-color: #1a56db;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .submit-btn:hover {
            background-color: #1345b4;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }
        
        .signup-link a {
            color: #1a56db;
            text-decoration: none;
            font-weight: 500;
        }
        
        .error-message {
            background-color: #fee2e2;
            color: #ef4444;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="login-title">Login Form</h1>
        
        <?php if (isset($error)) : ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="tab-navigation">
            <button type="button" class="tab-btn <?php echo $activeTab === 'login' ? 'active' : ''; ?>" onclick="switchTab('login')">Login</button>
            <button type="button" class="tab-btn <?php echo $activeTab === 'signup' ? 'active' : ''; ?>" onclick="switchTab('signup')">Signup</button>
        </div>
        
        <div id="login-tab" class="tab-content <?php echo $activeTab === 'login' ? 'active' : ''; ?>">
            <form method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <a href="#" class="forgot-password">Forgot password?</a>
                <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
            <div class="signup-link">
                Create an account <a href="?tab=signup">Signup now</a>
            </div>
        </div>
        
        <div id="signup-tab" class="tab-content <?php echo $activeTab === 'signup' ? 'active' : ''; ?>">
            <form method="POST">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="signup" class="submit-btn">Signup</button>
            </form>
            <div class="signup-link">
                Already have an account? <a href="?tab=login">Login now</a>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tab) {
            // Update URL without reloading the page
            history.pushState({}, '', '?tab=' + tab);
            
            // Update active tab
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.tab-btn:nth-child(${tab === 'login' ? '1' : '2'})`).classList.add('active');
            
            // Show active content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tab + '-tab').classList.add('active');
        }
    </script>
</body>
</html>