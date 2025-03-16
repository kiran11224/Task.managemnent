<?php
session_start();
include('connection.php');

// if (!isset($_SESSION['admin_id'])) {
//     die("Session Error: Admin ID not found!");
// } else {
//     echo "Admin Logged In: " . $_SESSION['admin_id']; // Debugging
// }

// Fetch all leave applications
$query = "SELECT leaves.*, users.name AS user_name FROM leaves 
          JOIN users ON leaves.user_id = users.id 
          ORDER BY leaves.id DESC";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leaves | Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f7;
        }
        .container {
            margin-top: 5px;
            max-width: 100%;
        }
        .card {
            background: white;
            padding: 5px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease-in-out;
        }
        .card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .status-pending { background-color: #ffc107; color: black; }
        .status-approved { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        .btn-approve { background-color: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
        .btn-reject { background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Manage Leave Requests</h2>

    <!-- Leave Requests Table -->
    <div class="card table-container mt-3">
        <h4 class="text-center mb-3">All Leave Requests</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>User</th>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Applied At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="leaveTable">
                <?php while ($leave = mysqli_fetch_assoc($result)): ?>
                    <tr id="leave-<?php echo $leave['id']; ?>">
                        <td><?php echo htmlspecialchars($leave['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?php echo $leave['start_date']; ?></td>
                        <td><?php echo $leave['end_date']; ?></td>
                        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                        <td><?php echo $leave['created_at']; ?></td>
                        <td>
                            <span class="badge 
                                <?php echo ($leave['status'] == 'Approved') ? 'status-approved' : (($leave['status'] == 'Rejected') ? 'status-rejected' : 'status-pending'); ?>">
                                <?php echo $leave['status']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($leave['status'] == 'Pending'): ?>
                                <button class="btn-approve change-status" data-id="<?php echo $leave['id']; ?>" data-status="Approved">Approve</button>
                                <button class="btn-reject change-status" data-id="<?php echo $leave['id']; ?>" data-status="Rejected">Reject</button>
                            <?php else: ?>
                                <span class="text-muted">Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(".change-status").click(function() {
        var leaveId = $(this).data("id");
        var newStatus = $(this).data("status");

        $.ajax({
            url: "update_leave_status.php",
            type: "POST",
            data: { leave_id: leaveId, status: newStatus },
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Leave status updated successfully!");
                    location.reload();
                } else {
                    alert("Error: " + response);
                }
            }
        });
    });
</script>

</body>
</html>
