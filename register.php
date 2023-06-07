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
