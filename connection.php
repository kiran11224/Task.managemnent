<?php
$host = "localhost";  // Change this if your database is hosted elsewhere
$user = "root";       // Your MySQL username (default: "root" for XAMPP)
$password = "";       // Your MySQL password (leave empty if using XAMPP)
$database = "ntms";  // Your database name

// Create connection
$connection = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
