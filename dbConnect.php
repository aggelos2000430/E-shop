<?php

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $host = 'localhost';
  $username = '';
  $password = '';
  $dbname = 'assignment';

  $conn = new mysqli($host, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>
