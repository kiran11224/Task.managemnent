<?php
session_start();
include('connection.php');

if (!isset($_SESSION['id'])) {
    die("Access Denied. Please log in.");
}

$user_id = $_SESSION['id'];

// Fetch tasks assigned to the logged-in user
$query = "SELECT * FROM tasks WHERE assigned_to = ? ORDER BY id DESC";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tasks</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
             font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .container { margin-top: 20px; }
        .table-container { background: white; padding: 20px; border-radius: 10px; }
        .editable {
            cursor: text; background-color: #f8f9fa; padding: 8px; border-radius: 5px; border: 1px dashed #ccc;
            min-height: 50px; max-height: 80px; width: 300px; overflow: hidden; white-space: nowrap;
            text-overflow: ellipsis; display: inline-block; text-align: left; overflow-y: scroll; overflow-x: hidden;
        }
        .editable:focus { background-color: white; border: 1px solid #007bff; outline: none; white-space: pre-wrap; overflow-y: auto; }
        .save-btn { font-size: 12px; padding: 3px 6px; border-radius: 4px; margin-left: 10px; }
        .status-container { display: flex; align-items: center; gap: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">My Tasks</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Task ID</th><th>Task Name</th><th>Description</th><th>Start Date</th>
                    <th>Due Date</th><th>Priority</th><th>Status & Save</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $task['id']; ?></td>
                        <td><?php echo htmlspecialchars($task['task_name']); ?></td>
                        <td><div class="editable" contenteditable="true" data-id="<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['task_desc']); ?></div></td>
                        <td><?php echo $task['start_date']; ?></td>
                        <td><?php echo $task['due_date']; ?></td>
                        <td><span class="badge bg-<?php echo ($task['priority'] == 'High') ? 'danger' : (($task['priority'] == 'Medium') ? 'warning' : 'success'); ?>"><?php echo $task['priority']; ?></span></td>
                        <td class="status-container">
                            <select class="form-select update-status" data-id="<?php echo $task['id']; ?>">
                                <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="In-Progress" <?php echo ($task['status'] == 'In-Progress') ? 'selected' : ''; ?>>In-Progress</option>
                                <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button class="btn btn-primary btn-sm save-btn" data-id="<?php echo $task['id']; ?>">Save</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(".editable").on("focus", function() {
        var el = this;
        setTimeout(function() {
            if (document.createRange) {
                var range = document.createRange();
                var selection = window.getSelection();
                range.selectNodeContents(el);
                range.collapse(false);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        }, 1);
    });

    $(".save-btn").click(function() {
        var taskId = $(this).data("id");
        var newDescription = $(this).closest("tr").find(".editable").text().trim();
        var newStatus = $(this).closest("tr").find(".update-status").val();
        var saveBtn = $(this);

        $.ajax({
            url: "update_task_status.php",
            type: "POST",
            data: { task_id: taskId, task_desc: newDescription, status: newStatus },
            success: function(response) {
                console.log("Server Response:", response);
                if (response.trim() === "success") {
                    alert("Task updated successfully!");
                    saveBtn.hide();
                } else {
                    alert("Error from server: " + response);
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", xhr.responseText);
                alert("AJAX request failed: " + xhr.status + " - " + xhr.statusText);
            }
        });
    });
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : null;
    $task_desc = isset($_POST['task_desc']) ? trim($_POST['task_desc']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;

    if (!$task_id || !$task_desc || !$status) {
        echo "error: Missing required data!";
        exit;
    }

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
    exit;
}
?>

</body>
</html>
