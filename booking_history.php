<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Periksa apakah ada pesan sukses/error dari proses hapus
$message = '';
if (isset($_SESSION['delete_message'])) {
    $message = $_SESSION['delete_message'];
    unset($_SESSION['delete_message']); // Hapus pesan setelah ditampilkan
}

// Inisialisasi variabel pencarian
$search_band = '';
$search_sql = ''; // Bagian WHERE untuk pencarian
$params = ""; // Tipe parameter (mulai kosong)
$param_values = []; // Nilai parameter (mulai kosong)

// Array untuk menyimpan referensi argumen bind_param
$bind_params_ref = [];

// Tambahkan parameter user_id
$params .= "i";
$param_values[] = $user_id;

// Cek apakah ada input pencarian
if (isset($_GET['search_band']) && !empty(trim($_GET['search_band']))) {
    $search_band = trim($_GET['search_band']);
    $search_sql = " AND b.band_name LIKE ?";
    $params .= "s"; // Tambahkan tipe 's' (string) untuk parameter band_name
    $param_values[] = "%" . $search_band . "%"; // Tambahkan nilai parameter untuk band_name
}

$query_string = "
    SELECT b.id, b.band_name, b.booking_date, b.booking_time, s.name AS studio_name, b.payment_status, b.payment_method
    FROM bookings b
    JOIN studios s ON b.studio_id = s.id
    WHERE b.user_id = ?" . $search_sql . "
    ORDER BY b.booking_date DESC, b.booking_time DESC
";
$query = $mysqli->prepare($query_string);

// --- PERBAIKAN UNTUK WARNING BIND_PARAM ---
// Argumen pertama untuk bind_param adalah string tipe
$bind_params_ref[] = &$params;

// Iterasi melalui nilai parameter dan tambahkan referensinya ke array
for ($i = 0; $i < count($param_values); $i++) {
    $bind_params_ref[] = &$param_values[$i];
}

// Panggil bind_param dengan array referensi
call_user_func_array([$query, 'bind_param'], $bind_params_ref);
// --- AKHIR PERBAIKAN ---

$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Booking Anda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f39466;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #eee;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .btn-bayar {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }
        /* Style untuk tombol hapus */
        .btn-delete {
            background-color: #f44336; /* Merah */
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }
        .btn-delete:hover {
            background-color: #da190b;
        }

        select {
            padding: 6px;
            border-radius: 5px;
            margin-top: 5px;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .centered {
            text-align: center;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .top-controls {
            display: flex;
            justify-content: space-between; /* Untuk meletakkan elemen di ujung */
            align-items: center; /* Untuk mensejajarkan secara vertikal */
            margin-bottom: 15px;
        }
        .search-form {
            display: flex;
            gap: 5px; /* Jarak antara input dan tombol */
        }
        .search-form input[type="text"],
        .search-form button {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-form button {
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        .search-form button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        // Fungsi konfirmasi hapus satuan
        function confirmDelete(bookingId) {
            return confirm("Apakah Anda yakin ingin menghapus booking ini?");
        }

        // Fungsi konfirmasi hapus semua
        function confirmDeleteAll() {
            return confirm("Apakah Anda yakin ingin MENGHAPUS SEMUA riwayat booking Anda? Tindakan ini tidak dapat dibatalkan.");
        }
    </script>
</head>
<body>

<h2>Riwayat Booking Anda</h2>

<?php if ($message): ?>
    <div class="message <?= strpos($message, 'berhasil') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="top-controls">
    <form method="GET" action="booking_history.php" class="search-form">
        <input type="text" name="search_band" placeholder="Cari Nama Band..." value="<?= htmlspecialchars($search_band) ?>">
        <button type="submit">Cari</button>
        <?php if (!empty($search_band)): ?>
            <a href="booking_history.php" style="text-decoration: none; margin-left: 5px;">
                <button type="button" class="btn-secondary" style="background-color: #ccc; color: black;">Reset</button>
            </a>
        <?php endif; ?>
    </form>

    <form method="POST" action="delete_booking.php" onsubmit="return confirmDeleteAll();">
        <input type="hidden" name="action" value="delete_all">
        <button type="submit" class="btn-delete">Hapus Semua Riwayat</button>
    </form>
</div>

<?php if ($result->num_rows > 0): ?>
<table>
    <thead>
        <tr>
            <th>Nama Band</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Studio</th>
            <th>Status Pembayaran</th>
            <th>Metode Pembayaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['band_name']) ?></td>
            <td><?= htmlspecialchars($row['booking_date']) ?></td>
            <td><?= htmlspecialchars($row['booking_time']) ?></td>
            <td><?= htmlspecialchars($row['studio_name']) ?></td>
            <td><?= $row['payment_status'] === 'paid' ? 'Lunas' : 'Belum Lunas' ?></td>
            <td><?= empty($row['payment_method']) ? '-' : htmlspecialchars($row['payment_method']) ?></td>

            <td>
                <?php if ($row['payment_status'] === 'pending'): ?>
                    <form method="GET" action="bayar_sekarang.php" style="display: inline-block; margin-right: 5px;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <select name="metode" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="DANA">DANA</option>
                            <option value="GoPay">GoPay</option>
                            <option value="SeaBank">SeaBank</option>
                            <option value="Allo Bank">Allo Bank</option>
                        </select><br>
                        <button type="submit" class="btn-bayar">Bayar Sekarang</button>
                    </form>
                <?php else: ?>
                    ✔️ Lunas
                <?php endif; ?>
                <form method="POST" action="delete_booking.php" onsubmit="return confirmDelete(<?= $row['id'] ?>);" style="display: inline-block;">
                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="action" value="delete_single">
                    <button type="submit" class="btn-delete">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
    <p class="centered"><em>Tidak ada riwayat booking.</em></p>
<?php endif; ?>

<a href="dashboard.php" class="back-button">⬅ Kembali ke Dashboard</a>

</body>
</html>