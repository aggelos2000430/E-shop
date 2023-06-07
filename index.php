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
  <title>E-Shop</title>
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
                        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
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
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY title DESC");
    $stmt->execute();
    $result = $stmt->get_result();

    while ($product = $result->fetch_assoc()) {
        echo '
            <div class="question">
                <h2>'.$product['title'].'</h2>
                <p>'.$product['description'].'</p>
                <p>$'.$product['price'].'</p>
                <a type="submit" href="addToCart.php?product_id='.$product['id'].'">Add to Cart</a>
            </div>
        ';
    }

    $stmt->close();

    ?>
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
