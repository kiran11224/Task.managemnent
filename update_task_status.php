<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;
    $task_desc = isset($_POST['task_desc']) ? trim($_POST['task_desc']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;

    // Validate inputs
    if (empty($task_id) || empty($task_desc) || empty($status)) {
        echo "error: Missing required data!";
        exit;
    }

    // SQL Query to update task description and status
    $query = "UPDATE tasks SET task_desc = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);

    if (!$stmt) {
        echo "error: SQL prepare failed - " . mysqli_error($connection);
        exit;
    }

    mysqli_stmt_bind_param($stmt, "ssi", $task_desc, $status, $task_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error: SQL execution failed - " . mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
}
?>
