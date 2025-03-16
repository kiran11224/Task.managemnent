<?php
session_start();
include('connection.php');

if (isset($_POST['adminLogin'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    

    // Fetch admin details
    $query = "SELECT * FROM admin WHERE email = '$email' AND password = '$password'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        
        // Store admin details in session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_name'] = $row['name'];  // Ensure your DB column is 'name'
        $_SESSION['admin_email'] = $row['email'];
        

        echo "<script>window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='admin_login.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Task Management System</title>

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9 !important;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px;
        }

        .login-container h4 {
            text-align: center;
            font-weight: bold;
            color: #333;
        }

        .btn-login {
            background-color: #343a40;
            border: none;
            font-size: 16px;
            padding: 10px;
            width: 100%;
            color: white;
        }

        .btn-login:hover {
            background-color: #23272b;
        }
    </style>
</head>
<body>

<!-- Admin Login Form -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-container">
        <h4><i class="fas fa-user-shield"></i> Admin Login</h4>
        <form action="" method="post">
            <div class="form-group mt-3">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group mt-3">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>
            <div class="form-group mt-4">
                <button type="submit" name="adminLogin" class="btn btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
            </div>
        </form>
        <center class="mt-3"><a href="../index.php" class="btn btn-danger btn-sm"><i class="fas fa-home"></i> Back to Home</a></center>
    </div>
</div>

</body>
</html>
