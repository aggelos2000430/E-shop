<?php
session_start();
require_once 'dbConnect.php';
?>
<!DOCTYPE html>
<html>
<!-- Include header here -->
<body>
    <h1>Your Cart</h1>
    <?php
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $product) {
            echo '<p>'.$product['title'].' - Quantity: '.$product['quantity'].' - $'.$product['price'].'</p>';
            echo '<a href="removeFromCart.php?product_id='.$product_id.'">Remove from Cart</a>';

            $total += $product['price'] * $product['quantity'];
        }
    } else {
        echo "<p>Your cart is empty.</p>";
    }

    echo '<h2>Total: $'.$total.'</h2>';
    ?>
<!-- Include footer here -->
</body>
</html>
