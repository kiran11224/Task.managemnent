<?php
include('connection.php');

header("Content-Type: text/plain"); // Ensures correct response format

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);

    // Check if the task exists before deleting
    $checkQuery = "SELECT id FROM tasks WHERE id = ?";
    $stmt = mysqli_prepare($connection, $checkQuery);
    mysqli_stmt_bind_param($stmt, "i", $task_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Perform the delete
        $deleteQuery = "DELETE FROM tasks WHERE id = ?";
        $stmt = mysqli_prepare($connection, $deleteQuery);
        mysqli_stmt_bind_param($stmt, "i", $task_id);

        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "Database Error: " . mysqli_error($connection);
        }
    } else {
        echo "error: Task not found.";
    }
} else {
    echo "error: Invalid request.";
}
?>
