<?php
session_start();
include('connection.php');

if (isset($_POST['userLogin'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $query_run = mysqli_query($connection, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['id'];

        echo "<script>window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Incorrect email or password!'); window.location.href = 'user_login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | TMS</title>

    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f6;
        }
        .container {
            height: 100vh;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="form-container">
        <h4 class="text-center"><i class="fas fa-sign-in-alt"></i> User Login</h4>
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
                <button type="submit" name="userLogin" class="btn btn-primary w-100"><i class="fas fa-user-check"></i> Login</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <a href="register.php" class="btn btn-secondary w-100"><i class="fas fa-user-plus"></i> Register</a>
            <a href="index.php" class="btn btn-danger w-100 mt-2"><i class="fas fa-home"></i> Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
