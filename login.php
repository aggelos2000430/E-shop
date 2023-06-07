<?php
session_start();
require_once 'dbConnect.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if($stmt->error) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;

                echo 'success';
                $response['redirect'] = 'index.php';
                exit;
            } else {
                echo 'Invalid username or password';
            exit;
            }
        } else {
            echo 'Invalid username or password';
        exit;
        }

        $stmt->close();
    } else {
        error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $conn->close();
}

$loggedin_status = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ? 'loggedin' : 'notloggedin';
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
                <button id="menu-btn" class="accordion2">
                  <div class="menu-bar"></div>
                  <div class="menu-bar"></div>
                  <div class="menu-bar"></div>
                </button>
                <div class="accordion2" id="menu-content">
                    <nav>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                        </ul>
                    </nav>
                </div>
          </div>
      </header>
      <main>
      <section class="container">
        <h2>Log in</h2>
        <?php
            if (isset($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            }
        ?>
        <div class="form-container">
            <form id="login-form" action="login.php" method="post">
                <nav>
                    <ul>
                        <label for="username">Username: </label>
                        <input type="text" id="username" name="username" placeholder="Username">
                        <label for="password">Password: </label>
                        <input type="password" id="password" name="password" placeholder="Password">
                        <button type="submit" id="login-button">Log In</button>
                        <p>Dont have an account? <li><a href="register.html">Register</a></li></p>
                    </ul>
                </nav>
            </form>
        </div>
    </section>
</main>
    <footer class="container">
      <p>&copy;2023 Technical Q&amp;A Community</p>
    </footer>
    <script src="accordion.js"></script>
    <script src="login.js"></script>
  </body>
</html>
