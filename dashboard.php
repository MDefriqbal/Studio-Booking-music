<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("Kumpulan Gambar/Backround Dashboard.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: white;
        }

        .container {
            text-align: center;
            padding: 80px 20px;
            background-color: rgba(0, 0, 0, 0.75);
            min-height: 100vh;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 40px;
            text-shadow: 2px 2px 5px black;
        }

        a.button {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        a.button:hover {
            background-color: #388e3c;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Selamat datang, <?= htmlspecialchars($_SESSION['role']) ?>!</h1>

        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="admin_booking.php" class="button">Kelola Booking</a>
            <a href="admin_notifications.php" class="button">Notifikasi</a>
        <?php else: ?>
            <a href="studio_list.php" class="button">Booking Studio</a>
            <a href="login_admin.php" class="button">Riwayat Booking</a>
        <?php endif; ?>

        <a href="logout.php" class="button">Logout</a>
    </div>

</body>
</html>
