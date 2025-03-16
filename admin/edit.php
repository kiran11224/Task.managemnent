<?php
session_start();
include('connection.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if task ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No task ID provided.");
}

$task_id = $_GET['id'];

// Fetch task details
$query = "SELECT * FROM tasks WHERE id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $task_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$task = mysqli_fetch_assoc($result);

if (!$task) {
    die("Error: Task not found.");
}

// Fetch all users for dropdown
$userQuery = "SELECT id, name FROM users";
$userResult = mysqli_query($connection, $userQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_name = mysqli_real_escape_string($connection, $_POST['task_name']);
    $task_desc = mysqli_real_escape_string($connection, $_POST['task_desc']);
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $assigned_to = $_POST['assigned_to'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    // Update query
    $updateQuery = "UPDATE tasks SET task_name = ?, task_desc = ?, start_date = ?, due_date = ?, assigned_to = ?, priority = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($connection, $updateQuery);
    mysqli_stmt_bind_param($stmt, "ssssissi", $task_name, $task_desc, $start_date, $due_date, $assigned_to, $priority, $status, $task_id);

    if (mysqli_stmt_execute($stmt)) {
     
     
        echo "<script>
        alert('Task Updated Successfully');
        window.location.href = 'admin_dashboard.php'
        </script>";
    } else {
        echo "<script>alert('Error updating task');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>

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
    <h3 class="mb-3">Edit Task</h3>
    
    <!-- Display Created At Date -->
    <p><strong>Created At:</strong> <?php echo $task['created_at']; ?></p>

    <form method="POST" action="">
        <div class="form-group mb-3">
            <label>Task Name</label>
            <input type="text" name="task_name" class="form-control" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
        </div>
        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="task_desc" class="form-control"><?php echo htmlspecialchars($task['task_desc']); ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="<?php echo $task['start_date']; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label>Due Date</label>
            <input type="date" name="due_date" class="form-control" value="<?php echo $task['due_date']; ?>" required>
        </div>
        <div class="form-group mb-3">
            <label>Assigned To</label>
            <select name="assigned_to" class="form-control" required>
                <option value="">Select User</option>
                <?php while ($user = mysqli_fetch_assoc($userResult)): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($user['id'] == $task['assigned_to']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group mb-3">
            <label>Priority</label>
            <select name="priority" class="form-control" required>
                <option value="High" <?php echo ($task['priority'] == 'High') ? 'selected' : ''; ?>>High Priority</option>
                <option value="Medium" <?php echo ($task['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium Priority</option>
                <option value="Low" <?php echo ($task['priority'] == 'Low') ? 'selected' : ''; ?>>Low Priority</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label>Status</label>
            <select name="status" class="form-control" required>
                <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="In Progress" <?php echo ($task['status'] == 'In-Progress') ? 'selected' : ''; ?>>In Progress</option>
                <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <button type="submit" class="btn btn-success w-100">Update Task</button>
        </div>
    </form>

    <a href="edit_tasks.php" class="btn btn-secondary w-100 mt-2">Back to Tasks</a>
</div>

</body>
</html>
