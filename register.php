<?php

session_start();
require_once 'dbConnect.php';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_error) {
    $response = array(
        'success' => false,
        'message' => 'Database connection failed: ' . $mysqli->connect_error
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$response = array(
    'success' => false,
    'message' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response['message'] = 'Username already taken';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    $stmt->close();

    // Check if email already exists
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $response['message'] = 'Email already in use';
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    $stmt->close();

    // If username and email are unique, register user
    $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt === false) {
        $response['message'] = 'Prepare statement failed: ' . $mysqli->error;
    } else {
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Registration successful';
        } else {
            $response['message'] = 'Registration failed: ' . $stmt->error;
        }

        $stmt->close();
    }
}

$mysqli->close();

header('Content-Type: application/json');
echo json_encode($response);

?>
