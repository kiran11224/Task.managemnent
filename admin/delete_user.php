<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $query = "DELETE FROM users WHERE id = $user_id";
    if (mysqli_query($connection, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
