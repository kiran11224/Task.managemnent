<?php
session_start();
include('connection.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo "<script>alert('Access Denied! Please log in first.'); window.location.href='admin_login.php';</script>";
    exit();
}

// Fetch all users
$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin Panel</title>

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .table-container {
            max-height: 70vh;
            overflow-y: scroll;
        }
        .table thead {
            position: sticky;
            top: 0;
            background: #343a40 !important;
            color: white !important;
        }
        .search-bar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Manage Users</h2>

    <!-- Search Bar -->
    <input type="text" id="searchInput" class="form-control search-bar" placeholder="Search users by name or email...">

    <div class="table-container">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>S.No.</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php 
                $serialNo = 1; // Initialize serial number
                while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr id="user-<?php echo $user['id']; ?>">
                        <td><?php echo $serialNo++; ?></td>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['mobile']); ?></td>
                        <td>
                            <button class="btn btn-danger delete-user" data-id="<?php echo $user['id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
$(document).ready(function(){
    // Search Functionality
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Delete User Functionality
    $(".delete-user").on("click", function() {
        var userId = $(this).data("id");

        if (confirm("Are you sure you want to delete this user?")) {
            $.post("delete_user.php", { user_id: userId }, function(response) {
                if (response.trim() === "success") {
                    $("#user-" + userId).remove();
                    alert("User deleted successfully!");
                } else {
                    alert("Error deleting user.");
                }
            });
        }
    });
});
</script>

</body>
</html>
