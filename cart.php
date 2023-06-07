<?php
session_start();
$loggedin_status = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'loggedin' : 'notloggedin';
require_once 'dbConnect.php';
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html>
<head>
  <link id="theme" rel="stylesheet" href="light.css">
  <script src="toggleTheme.js"></script>
  <title>Cart - E-Shop</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body class="light-mode">
  <header>
    <button class="accordion2" onclick="toggleTheme()">Theme</button>
        <h1>E-Shop</h1>
        <div class="menu-container">
            <button class="accordion2" id="menu-btn">
                <div class="menu-bar"></div>
                <div class="menu-bar"></div>
                <div class="menu-bar"></div>
            </button>
            <div class="accordion2" id="menu-content">
                <nav>
                    <ul>
                        <li><a href="index.php"></i>Home</a></li>
                        <?php
                            if (trim($loggedin_status) === 'loggedin') {
                                echo '<li><a href="logout.php">Log Out</a></li>';
                            } else {
                                echo '<li><a href="login.php">Log In</a></li>';
                            }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
  </header>
<main>
    <div class="product-grid">
    <?php
    $totalPrice = 0;
    if (!empty($_SESSION['cart'])) {
        $counter = 1;
        foreach ($_SESSION['cart'] as $product_id => $product_details) {
            $itemTotal = $product_details['quantity'] * $product_details['price'];
            $totalPrice += $itemTotal;
            echo '
            <div class="question">
                <form action="updateQuantity.php" method="post">
                    <h3>' . $counter . '. <input type="text" name="title" value="' . $product_details['title'] . '" readonly></h3>
                    Item Price: $' . number_format($product_details['price'], 2) . '
                    <label for="quantity">Quantity: </label>
                    <input type="number" id="quantity" name="quantity" value="' . $product_details['quantity'] . '" min="1">
                    <input type="hidden" name="product_id" value="' . $product_id . '">
                    <button type="submit" name="update">Update Quantity</button>
                    <a href="removeFromCart.php?product_id=' . $product_id . '"><input type="button" value="Remove"></a>
                </form>
            </div>';
            $counter++;
        }
    } else {
        echo '<p>Your cart is empty. <a href="index.php">Go back to shop.</a></p>';
    }
    ?>
    </div>
    <div class="total-price">
        <h2>Total Price: $<?php echo number_format($totalPrice, 2); ?></h2>
    </div>
    <div class="checkout">
        <form action="checkout.php" method="post">
            <button type="submit">Checkout</button>
        </form>
    </div>
</main>
  <footer class="container">
    <p>&copy;2023 E-Shop</p>
  </footer>
  <script src="accordion.js"></script>
  <script>
    window.addEventListener('beforeunload', function() {
        localStorage.setItem('scrollPosition', window.pageYOffset);
    });

    window.addEventListener('DOMContentLoaded', (event) => {
        const scrollPosition = localStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                localStorage.removeItem('scrollPosition');
            }
    });
  </script>
</body>
</html>

