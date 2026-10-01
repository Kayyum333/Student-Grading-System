<?php

$host = "localhost";
$username = "root";
$password = "project";
$database = "project";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// echo "Database connected successfully!";

?>