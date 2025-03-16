<?php
session_start();
include('connection.php');

// Fetch all tasks with assigned user names
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
            margin-top: 5px;
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
    <h2 class="mb-4">Manage Tasks</h2>

    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Task ID</th>
                    <th>Task Name</th>
                    <th>Assigned To</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><?php echo htmlspecialchars($task['assigned_user']); ?></td>
                        <td><?php echo $task['start_date']; ?></td>
                        <td><?php echo $task['due_date']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($task['priority'] == 'High') ? 'danger' : (($task['priority'] == 'Medium') ? 'warning' : 'success'); ?>">
                                <?php echo $task['priority']; ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo ($task['status'] == 'Pending') ? 'secondary' : (($task['status'] == 'In-Progress') ? 'primary' : 'success'); ?>">
                                <?php echo $task['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $task['created_at']; ?></td>
                        <td>
                        <a href="edit.php?id=<?php echo $task['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm delete-task" data-id="<?php echo $task['id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).on("click", ".delete-task", function(){
        if (confirm("Are you sure you want to delete this task?")) {
            var taskId = $(this).data("id");

            $.ajax({
                url: "delete_task.php",
                type: "POST",
                data: { task_id: taskId },
                success: function(response) {
                    console.log("Server Response:", response); // Debugging
                    if (response.trim() === "success") {
                        alert("Task deleted successfully!");
                        location.reload(); // Reload the page after successful delete
                    } else {
                        alert("Error: " + response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX Error:", textStatus, errorThrown);
                    alert("AJAX request failed: " + textStatus + " - " + errorThrown);
                }
            });
        }
    });
</script>





</body>
</html>
