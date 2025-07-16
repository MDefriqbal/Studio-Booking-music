<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Login admin hardcoded
    if ($username == 'admin' && $password == 'admin') {
        $_SESSION['is_admin'] = true;
        header("Location: booking_history.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url("Kumpulan Gambar/L0gin admin.jpg"); /* Gunakan gambar gothic kamu */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        .login-box {
            background: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
            text-align: center;
            width: 300px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background: #f0f4ff;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1e7e34;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

        .info {
            margin-top: 10px;
            font-size: 13px;
            color: #ccc;
        }

        .info a {
            color: #4fc3f7;
            text-decoration: none;
        }

        .info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form class="login-box" method="POST" action="">
        <h2>Login Admin</h2>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <div class="info">
            Hanya khusus admin saja <br>
            <a href="dashboard.php">Kembali ke dashboard</a>
        </div>
    </form>
</body>
</html>
