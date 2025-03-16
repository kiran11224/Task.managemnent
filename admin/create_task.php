<?php
session_start();
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $task_name = mysqli_real_escape_string($connection, $_POST['task_name']);
    $task_desc = mysqli_real_escape_string($connection, $_POST['task_desc']);
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $assigned_to = $_POST['assigned_to']; // Ensure this is a valid user ID
    $priority = $_POST['priority'];
    $status = "Pending"; // Default status

    // Check if assigned user exists
    $userCheckQuery = "SELECT id FROM users WHERE id = ?";
    $stmt = mysqli_prepare($connection, $userCheckQuery);
    mysqli_stmt_bind_param($stmt, "i", $assigned_to);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Insert task if the user exists
        $insertQuery = "INSERT INTO tasks (task_name, task_desc, start_date, due_date, assigned_to, priority, status) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssssiss", $task_name, $task_desc, $start_date, $due_date, $assigned_to, $priority, $status);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
            alert('Task Created Successfully');
            window.location.href = 'admin_dashboard.php';
            </script>";
        
        } else {
            echo "<script>alert('Error creating task');</script>";
        }
    } else {
        echo "<script>alert('Invalid user selected for assignment.');</script>";
    }

    mysqli_stmt_close($stmt);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Task</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class="mb-3">Create Task</h3>
    <form method="POST" action="">
        <div class="form-group mb-3">
            <label>Task Name</label>
            <input type="text" name="task_name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="task_desc" class="form-control"></textarea>
        </div>
        <div class="form-group mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>
        <div class="form-group mb-3">
    <label>Assigned To</label>
    <select name="assigned_to" class="form-control" required>
        <option value="">Select User</option>
        <?php
        $userQuery = "SELECT id, name FROM users";
        $userResult = mysqli_query($connection, $userQuery);
        while ($user = mysqli_fetch_assoc($userResult)) {
            echo "<option value='{$user['id']}'>{$user['name']}</option>";
        }
        ?>
    </select>
</div>

        <div class="form-group mb-3">
            <label>Priority</label>
            <select name="priority" class="form-control" required>
                <option value="High">High Priority</option>
                <option value="Medium">Medium Priority</option>
                <option value="Low">Low Priority</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-success w-100">Create Task</button>
        </div>
    </form>

    <a href="admin_dashboard.php" class="btn btn-secondary w-100">Back to Dashboard</a>
</div>

</body>
</html>
