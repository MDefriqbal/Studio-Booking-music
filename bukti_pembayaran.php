<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID booking tidak ditemukan.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir);
    $target_file = $target_dir . basename($_FILES['bukti']['name']);

    if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("UPDATE bookings SET bukti_pembayaran=? WHERE id=?");
        $stmt->bind_param("si", $target_file, $id);
        $stmt->execute();
        $status = "<p class='success'>✅ Bukti pembayaran berhasil diupload.</p>";
    } else {
        $status = "<p class='error'>❌ Upload gagal.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Bukti Pembayaran</title>
    <style>
        body {
            background-color: #2e3b42;
            color: #f5f5f5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #37474f;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            text-align: center;
            color: #ffffff;
        }
        input[type='file'] {
            width: 100%;
            margin: 15px 0;
            padding: 8px;
            background-color: #263238;
            border: 1px solid #555;
            color: white;
            border-radius: 6px;
        }
        button {
            background-color: #29b6f6;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        button:hover {
            background-color: #0288d1;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #81d4fa;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .success { color: #a5d6a7; }
        .error { color: #ef9a9a; }
    </style>
</head>
<body>
<div class="container">
    <h2>Upload Bukti Pembayaran</h2>
    <?= $status ?? '' ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="bukti" accept="image/*" required>
        <button type="submit">Upload</button>
    </form>
    <a href="booking_history.php">⬅ Kembali ke Riwayat Booking</a>
</div>
</body>
</html>