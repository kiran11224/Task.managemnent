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
    <title>Apply Leave | User Dashboard</title>
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
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .hidden {
            display: none;
        }
        .popup {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: green;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Leave Management</h2>

    <!-- Apply Leave Button -->
    <div class="text-center mb-3">
        <button class="btn btn-primary" id="toggleLeaveForm"><i class="fas fa-calendar-plus"></i> Apply Leave</button>
    </div>

    <!-- Leave Application Form (Hidden Initially) -->
    <div class="card hidden" id="leaveFormContainer">
        <form id="leaveForm">
            <h4 class="text-center mb-3">Apply for Leave</h4>

            <div class="mb-3">
                <label class="form-label">Leave Type</label>
                <select class="form-select" id="leave_type" required>
                    <option value="">Select Leave Type</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Casual Leave">Casual Leave</option>
                    <option value="Annual Leave">Annual Leave</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" required>
            </div>

            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Reason</label>
                <textarea class="form-control" id="reason" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-success w-100">Submit Leave Request</button>
        </form>
    </div>

    <!-- Leave Requests Table -->
    <div class="card table-container mt-3">
        <h4 class="text-center mb-3">My Leave Requests</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Leave Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Reason</th>
                    <th>Applied At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="leaveTable">
                <?php while ($leave = mysqli_fetch_assoc($result)): ?>
                    <tr id="leave-<?php echo $leave['id']; ?>">
                        <td><?php echo htmlspecialchars($leave['leave_type']); ?></td>
                        <td><?php echo $leave['start_date']; ?></td>
                        <td><?php echo $leave['end_date']; ?></td>
                        <td><?php echo htmlspecialchars($leave['reason']); ?></td>
                        <td><?php echo $leave['created_at']; ?></td>
                        <td>
                            <button class="btn-delete delete-leave" data-id="<?php echo $leave['id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Success Popup -->
<div class="popup" id="successPopup">✅ Leave Applied Successfully !</div>

<script>
    // Toggle Leave Form Visibility
    $("#toggleLeaveForm").click(function() {
        $("#leaveFormContainer").removeClass("hidden");
        $("#toggleLeaveForm").hide(); // Hide button after click
    });

    // Apply Leave Request via AJAX
    $("#leaveForm").submit(function(e) {
        e.preventDefault();

        var leaveType = $("#leave_type").val();
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        var reason = $("#reason").val();

        $.ajax({
            url: "process_leave.php",
            type: "POST",
            data: { leave_type: leaveType, start_date: startDate, end_date: endDate, reason: reason },
            success: function(response) {
                if (response.trim() === "success") {
                    $("#successPopup").fadeIn().delay(2000).fadeOut();
                    setTimeout(() => location.reload(), 2000); 
                } else {
                    alert("Error: " + response);
                }
            }
        });
    });

    // Delete Leave Request via AJAX
    $(document).on("click", ".delete-leave", function() {
        if (!confirm("Are you sure you want to delete this leave request?")) return;

        var leaveId = $(this).data("id");

        $.ajax({
            url: "delete_leave.php",
            type: "POST",
            data: { leave_id: leaveId },
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Leave request deleted successfully!");
                    $("#leave-" + leaveId).fadeOut(500, function() { $(this).remove(); });
                } else {
                    alert("Error: " + response);
                }
            }
        });
    });
</script>

</body>
</html>
