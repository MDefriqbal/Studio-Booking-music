<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID booking tidak ditemukan.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metode = $_POST['metode'] ?? 'GoPay';

    $stmt = $conn->prepare("UPDATE bookings SET payment_status='paid', payment_method=? WHERE id=?");
    $stmt->bind_param("si", $metode, $id);
    $stmt->execute();

    echo "<div class='container'>";
    echo "<h2>Pembayaran berhasil dikonfirmasi dengan metode <span class='highlight'>" . htmlspecialchars($metode) . "</span>.</h2>";
    echo "<a class='btn' href='booking_history.php'>Kembali ke Riwayat Booking</a>";
    echo "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f8f8f8, #e8eaf6);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 80px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        .radio-option {
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            background-color: #3f51b5;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #303f9f;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #3f51b5;
        }

        .highlight {
            color: #4caf50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Konfirmasi Pembayaran</h2>
        <form method="POST">
            <label>Pilih Metode Pembayaran:</label>
            <div class="radio-option">
                <input type="radio" name="metode" value="GoPay" checked> GoPay
            </div>
            <div class="radio-option">
                <input type="radio" name="metode" value="DANA"> DANA
            </div>
            <br>
            <button class="btn" type="submit">Konfirmasi Pembayaran</button>
        </form>
        <a class="back-link" href="booking_history.php">Kembali</a>
    </div>
</body>
</html>
