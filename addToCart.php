<?php
session_start();
require_once 'dbConnect.php';

if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Query to get product details
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Add product to the cart
    if($product) {
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
            $_SESSION['message'] = "Product added successfully!";
        } else {
            $_SESSION['cart'][$product_id] = array(
                'quantity' => 1,
                'title' => $product['title'],
                'price' => $product['price']
            );
            $_SESSION['message'] = "Product added successfully!";
        }
    } else {
        $_SESSION['message'] = "Error: Product not found.";
    }

    header('Location: cart.php');
}
