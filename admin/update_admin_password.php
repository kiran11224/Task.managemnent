<?php
include('connection.php');
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "Access denied!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $admin_email = $_SESSION['email'];

    $query = "UPDATE admin SET password='$new_password' WHERE email='$admin_email'";

    if (mysqli_query($connection, $query)) {
        echo "Admin password updated successfully!";
    } else {
        echo "Error updating password: " . mysqli_error($connection);
    }
}
?>
