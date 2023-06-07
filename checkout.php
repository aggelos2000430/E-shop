<?php
session_start();
require_once 'dbConnect.php';

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if (empty($name)) {
        $errors['name'] = 'required.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = '*required.';
    }

    if (empty($phone) || !preg_match("/^[0-9]{10}$/", $phone)) {
        $errors['phone'] = '*required.';
    }

    if (empty($address)) {
        $errors['address'] = '*required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('isssss', $_SESSION['user_id'], $name, $email, $phone, $address, $notes);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            $success = 'Your order has been placed successfully!';
        } else {
            $errors['db'] = 'There was an error placing your order. Please try again.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <link id="theme" rel="stylesheet" href="light.css">
  <script src="toggleTheme.js"></script>
  <title>Checkout - E-Shop</title>
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
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    <?php
                        $loggedin_status = isset($_SESSION['user_id']) ? 'loggedin' : 'loggedout';
                        if (trim($loggedin_status) === 'loggedin') {
                            echo '<li><a href="myprofile.php" id="profile-link">My Profile</a></li>';
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
                      <h3>' . $counter . '. ' . $product_details['title'] . '</h3>
                      Item Price: $' . number_format($product_details['price'], 2) . '
                      Quantity: ' . $product_details['quantity'] . '
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
        <?php if (isset($success)): ?>
            <div class="success">
                <?php echo $success; ?>
            </div>
        <?php else: ?>
            <form action="" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                <?php if (isset($errors['name'])): ?><div class="error"><?php echo $errors['name']; ?></div><?php endif; ?>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (isset($errors['email'])): ?><div class="error"><?php echo $errors['email']; ?></div><?php endif; ?>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                <?php if (isset($errors['phone'])): ?><div class="error"><?php echo $errors['phone']; ?></div><?php endif; ?>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
                <?php if (isset($errors['address'])): ?><div class="error"><?php echo $errors['address']; ?></div><?php endif; ?>

                <label for="notes">Notes (optional):</label>
                <textarea id="notes" name="notes"><?php echo htmlspecialchars($notes); ?></textarea>

                <button type="submit">Place Order</button>
            </form>
        <?php endif; ?>
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
