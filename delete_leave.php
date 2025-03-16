<?php
session_start();
include('connection.php');


if (!isset($_SESSION['id'])) {
    echo "error: Unauthorized Access!";
    exit;
}

$leave_id = isset($_POST['leave_id']) ? intval($_POST['leave_id']) : null;
$user_id = $_SESSION['id'];

if (!$leave_id) {
    echo "error: Missing leave ID!";
    exit;
}

// Check if the leave request exists and is still pending
$check_query = "SELECT id, status FROM leaves WHERE id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($connection, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $leave_id, $user_id);
mysqli_stmt_execute($check_stmt);
mysqli_stmt_store_result($check_stmt);

if (mysqli_stmt_num_rows($check_stmt) === 0) {
    echo "error: Leave request not found!";
    exit;
}

// Fetch leave status
mysqli_stmt_bind_result($check_stmt, $id, $status);
mysqli_stmt_fetch($check_stmt);
mysqli_stmt_close($check_stmt);

if ($status !== "Pending") {
    echo "error: You can only delete pending leave requests!";
    exit;
}

// Proceed with deletion
$query = "DELETE FROM leaves WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ii", $leave_id, $user_id);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($connection);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
