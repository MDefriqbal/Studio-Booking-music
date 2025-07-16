<?php
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = 'user';

    // Cek apakah username sudah ada
    $check = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $mysqli->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed, $role);
        $stmt->execute();
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register Akun</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-image: url('Kumpulan Gambar/Hitam Abu-Abu Abstrak Y2K Streetwear Logo.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
            background-color: #f2f2f2;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            color: #fff;
            width: 350px;
            text-align: center;
        }

        .register-box h2 {
            margin-bottom: 25px;
        }

        .register-box input[type="text"],
        .register-box input[type="password"],
        .register-box button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .register-box input {
            background: #f1f1f1;
            color: #333;
        }

        .register-box button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .register-box button:hover {
            background-color: #388e3c;
        }

        .error {
            color: #ff4c4c;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .login-link {
            font-size: 14px;
            color: #ccc;
            margin-top: 10px;
            display: block;
        }

        .login-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Register Akun</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Daftar</button>
        </form>
        <div class="login-link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>
</html>
