<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Studio Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('Hitam Abu-Abu Abstrak Y2K Streetwear Logo.png') no-repeat center center;
            background-size: 50%;
            background-color: #f4f4f4;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container {
            background: rgba(0,0,0,0.7);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.4);
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            background: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            margin: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn:hover {
            background: #388e3c;
        }

        .register {
            background-color: #007BFF;
        }

        .register:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Selamat Datang di Aplikasi Booking Studio Musik</h1>
    <a href="login.php" class="btn">Login</a>
    <a href="register.php" class="btn register">Daftar</a>
</div>

</body>
</html>
