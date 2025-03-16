<?php
include('connection.php');
session_start();

if (!isset($_SESSION['id'])) {
    die("Access Denied.");
}

$user_id = $_SESSION['id'];
$leave_type = isset($_POST['leave_type']) ? trim($_POST['leave_type']) : null;
$start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : null;
$end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : null;

if (!$leave_type || !$start_date || !$end_date || !$reason) {
    echo "error: Missing required fields!";
    exit;
}

// Insert into database
$query = "INSERT INTO leaves (user_id, leave_type, start_date, end_date, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "issss", $user_id, $leave_type, $start_date, $end_date, $reason);

if (mysqli_stmt_execute($stmt)) {
    echo "success";
} else {
    echo "error: " . mysqli_error($connection);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
