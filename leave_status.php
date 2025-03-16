<?php
session_start();
include('connection.php');

if (!isset($_SESSION['id'])) {
    die("Access Denied. Please log in.");
}

$user_id = $_SESSION['id'];

// Fetch user leaves
$query = "SELECT * FROM leaves WHERE user_id = ? ORDER BY id DESC";
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
    <title>Leave Status | User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #eef2f7;
        }
        .container {
            margin-top: 30px;
            max-width: 850px;
        }
        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .badge-status {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
        }
        .status-pending {
            background-color: #ffc107;
            color: #000;
        }
        .status-approved {
            background-color: #28a745;
            color: #fff;
        }
        .status-rejected {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Leave Status</h2>

    <!-- Leave Status Table -->
    <div class="card">
        <h4 class="text-center mb-3">My Leave Requests</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Applied At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($leave = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?php echo $leave['start_date']; ?></td>
                        <td><?php echo $leave['end_date']; ?></td>
                        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                        <td><?php echo $leave['created_at']; ?></td>
                        <td>
                            <span class="badge badge-status 
                                <?php echo ($leave['status'] == 'Approved') ? 'status-approved' : (($leave['status'] == 'Rejected') ? 'status-rejected' : 'status-pending'); ?>">
                                <?php echo $leave['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
