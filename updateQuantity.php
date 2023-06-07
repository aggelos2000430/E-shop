<?php
session_start();

if(isset($_POST['quantity']) && isset($_POST['product_id'])) {
    $newQuantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);

    if($newQuantity > 0) {
        if(array_key_exists($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            echo "Error: Product not found in cart.";
        }
    } else {
        echo "Error: Invalid quantity.";
    }
} else {
    echo "Error: Missing quantity or product ID.";
}

header("Location: cart.php");
exit;
?>

