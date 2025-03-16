<?php
include('connection.php');
session_start();

// if (!isset($_SESSION['admin_id'])) {
//     echo "error 2: Unauthorized Access!";
//     exit;
// }

$leave_id = isset($_POST['leave_id']) ? intval($_POST['leave_id']) : null;
$status = isset($_POST['status']) ? trim($_POST['status']) : null;

if (!$leave_id || !$status) {
    echo "error: Missing required data!";
    exit;
}

// Update leave status in the database
$query = "UPDATE leaves SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "si", $status, $leave_id);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($connection);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
