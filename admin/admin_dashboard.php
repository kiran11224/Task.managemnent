<?php
session_start();
include('connection.php');

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied! Please log in first.');
    window.location.href='admin_login.php';</script>";
    exit;
}

// Fetch session variables
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';
$admin_email = isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : 'No Email';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Task Management System</title>
    
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

        /* Top Bar */
        .top-bar {
            width: 100%;
            background: linear-gradient(to right,rgb(34, 60, 88),rgb(23, 59, 66));
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .top-bar .admin-info {
            font-size: 16px;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #343a40;
            color: white;
            position: fixed;
            top: 60px;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
            border-bottom: 1px solid #495057;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: #495057;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        /* Right Content */
        .content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Dashboard Cards */
        .dashboard-box {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            background: linear-gradient(to right,rgb(33, 92, 155),rgb(3, 103, 122));
            color: white;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function(){
            $(".menu-link").click(function(e){
                e.preventDefault();
                var page = $(this).attr("href");

                // Prevent AJAX for dashboard
                if (page !== "admin_dashboard.php") {
                    $("#content-area").load(page);
                } else {
                    window.location.href = "admin_dashboard.php";
                }
            });
        });
    </script>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <h4>Admin Panel</h4>
    <div class="admin-info">
        <i class="fas fa-user-circle"></i> <?php echo $admin_name; ?> | <i class="fas fa-envelope"></i> <?php echo $admin_email; ?>
    </div>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <a href="admin_dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="create_tasks.php" class="menu-link"><i class="fas fa-tasks"></i> Create Task</a>
    <a href="edit_tasks.php" class="menu-link"><i class="fas fa-edit"></i> Edit Task</a>
    <a href="view_leaves.php" class="menu-link"><i class="fas fa-calendar-alt"></i> View Leave Requests</a>
    <a href="manage_users.php" class="menu-link"><i class="fas fa-users"></i> Manage Users</a>
    <a href="admin_logout.php" class="bg-danger text-white"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<!-- Right Content -->
<div class="content">
    <div id="content-area">
        <h2>Welcome to Admin Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="dashboard-box">
                    <i class="fas fa-users fa-2x"></i>
                    <h5>Users</h5>
                    <p>Manage all users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box">
                    <i class="fas fa-tasks fa-2x"></i>
                    <h5>Tasks</h5>
                    <p>Monitor all tasks</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-box">
                    <i class="fas fa-calendar-alt fa-2x"></i>
                    <h5>Leaves</h5>
                    <p>Manage leave requests</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
