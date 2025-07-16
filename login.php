<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_pw, $role);

    if ($stmt->fetch() && password_verify($password, $hashed_pw)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Login gagal! Username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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

        .login-box {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            color: #fff;
            width: 350px;
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
        }

        .login-box input[type="text"],
        .login-box input[type="password"],
        .login-box button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .login-box input {
            background: #e6edff;
            color: #333;
        }

        .login-box button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .login-box button:hover {
            background-color: #388e3c;
        }

        .error {
            color: #ff4c4c;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .additional-links {
            font-size: 14px;
            margin-top: 10px;
        }

        .additional-links a {
            color: #4CAF50;
            text-decoration: none;
        }

        .additional-links a:hover {
            text-decoration: underline;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 40px;
        }

        .password-container span {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #4b4b4b;
            line-height: 1;
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span id="togglePassword">👁️</span>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="additional-links">
            Belum punya akun? <a href="register.php">Daftar di sini</a><br>
            <a href="forgot_password.php">Lupa password?</a>
        </div>
    </div>

    <script>
        const toggle = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        toggle.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
