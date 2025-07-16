<?php
session_start(); // Pastikan session dimulai di awal file

if (isset($_SESSION['struk_data'])) {
    $struk_data = $_SESSION['struk_data'];
    // Hapus data session setelah digunakan agar tidak muncul lagi saat di-refresh
    unset($_SESSION['struk_data']);
} else {
    // Jika tidak ada data struk, mungkin ada yang salah atau diakses langsung
    echo "Data struk tidak tersedia.";
    // Atau redirect ke halaman lain
    // header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        /* Gaya CSS yang sama seperti di bayar_sekarang.php atau sesuaikan */
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
        .info p {
            margin: 8px 0;
            text-align: left;
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
    </style>
</head>
<body>

<div class="card">
    <h2>Struk Pembayaran</h2>
    <div class="info">
        <p><strong>No. Reservasi:</strong> <?= htmlspecialchars($struk_data['no_reservasi']) ?></p>
        <p><strong>Nama Band:</strong> <?= htmlspecialchars($struk_data['nama_band']) ?></p>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($struk_data['tanggal']) ?></p>
        <p><strong>Jam:</strong> <?= htmlspecialchars($struk_data['jam']) ?></p>
        <p><strong>Studio:</strong> <?= htmlspecialchars($struk_data['studio']) ?></p>
        <p><strong>Metode Bayar:</strong> <?= htmlspecialchars($struk_data['metode_bayar']) ?></p>
        <p><strong>Total Bayar:</strong> Rp <?= number_format($struk_data['total_bayar'], 0, ',', '.') ?></p>
    </div>

    <p style="margin-top: 20px;">Pembayaran Anda telah berhasil dikonfirmasi.</p>

    <button class="btn" onclick="window.print()">Cetak Struk</button>
    <br>
    <a href="dashboard.php"><button class="btn btn-secondary">Kembali ke Dashboard</button></a>
</div>

</body>
</html>