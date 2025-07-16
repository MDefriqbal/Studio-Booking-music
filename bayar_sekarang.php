<?php
include 'config.php';

if (!isset($_GET['id']) || !isset($_GET['metode'])) {
    echo "ID Booking atau metode pembayaran tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$metode = $_GET['metode'];

// Ambil data booking + studio
// TELAH DIPERBAIKI: Menggunakan s.name AS studio_name sesuai dengan skema database Anda
$query = $mysqli->prepare("
    SELECT b.*, s.name AS studio_name, s.price
    FROM bookings b
    JOIN studios s ON b.studio_id = s.id
    WHERE b.id = ?
");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Data booking tidak ditemukan.";
    exit;
}

// Tentukan gambar QR berdasarkan metode
// TELAH DIPERBAIKI: Semua metode pembayaran menggunakan 'QR code.png'
$qr_files = [
    'DANA'      => 'QR code.png',
    'GoPay'     => 'QR code.png',
    'SeaBank'   => 'QR code.png',
    'Allo Bank' => 'QR code.png'
];

// Jika metode tidak terdaftar, tetap gunakan 'QR code.png'
$qr_image = isset($qr_files[$metode]) ? $qr_files[$metode] : 'QR code.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
            padding: 50px;
        }
        .card {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card h2 {
            margin-top: 0;
        }
        .info p {
            margin: 8px 0;
            text-align: left;
        }
        .qr-code {
            margin: 20px 0;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-secondary {
            background-color: #ccc;
            color: black;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Konfirmasi Pembayaran</h2>

    <div class="info">
        <p><strong>Nama Band:</strong> <?= htmlspecialchars($data['band_name']) ?></p>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($data['booking_date']) ?></p>
        <p><strong>Jam:</strong> <?= htmlspecialchars($data['booking_time']) ?></p>
        <p><strong>Studio:</strong> <?= htmlspecialchars($data['studio_name']) ?></p>
        <p><strong>Total Bayar:</strong> Rp <?= number_format($data['price'], 0, ',', '.') ?></p>
    </div>

    <p>Silakan selesaikan pembayaran via:</p>
    <h3><?= htmlspecialchars($metode) ?></h3>

    <div class="qr-code">
        <img src="Kumpulan Gambar/<?= $qr_image ?>" alt="QR Code Pembayaran" width="200">
        <p>Scan QR code untuk membayar</p>
    </div>

    <form action="konfirmasi_pembayaran.php" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
        <input type="hidden" name="metode" value="<?= htmlspecialchars($metode) ?>">
        <button type="submit" class="btn">Saya Sudah Bayar</button>
    </form>

    <br>
    <a href="booking_history.php"><button class="btn btn-secondary">Ganti Metode Pembayaran</button></a>
</div>

</body>
</html>