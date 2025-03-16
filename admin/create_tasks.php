<?php
session_start();
include('connection.php');

// Fetch all tasks with assigned user names and created_at timestamp
$query = "SELECT tasks.*, users.name AS assigned_user FROM tasks 
          JOIN users ON tasks.assigned_to = users.id 
          ORDER BY tasks.id DESC";

$result = mysqli_query($connection, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks | Admin Panel</title>

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="create_task.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Create Task
    </a>

    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Task ID</th>
                    <th>Task Name</th>
                    <th>Assigned To</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Created At</th> <!-- New Column -->
                </tr>
            </thead>
            <tbody>
                <?php while ($task = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_user']); ?></td> <!-- Shows User Name -->
                        <td><?php echo $task['start_date']; ?></td>
                        <td><?php echo $task['due_date']; ?></td>
                        <td>
                            <span class="badge bg-warning"><?php echo $task['status']; ?></span>
                        </td>
                        <td><?php echo $task['created_at']; ?></td> <!-- Shows Created Date -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
