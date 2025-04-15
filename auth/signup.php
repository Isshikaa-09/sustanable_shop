<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if ($query->execute([$name, $email, $password])) {
        echo "Signup successful!";
    } else {
        echo "Signup failed.";
    }
}
?>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Sign Up</button>
</form>
