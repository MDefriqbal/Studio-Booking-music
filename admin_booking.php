<?php
session_start();
include 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$bookings = $mysqli->query("SELECT b.id, u.username, s.name AS studio, b.band_name, b.booking_date, b.booking_time 
                            FROM bookings b 
                            JOIN users u ON b.user_id = u.id 
                            JOIN studios s ON b.studio_id = s.id");

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $mysqli->query("DELETE FROM bookings WHERE id=$id");
    header("Location: admin_booking.php");
}
?>

<link rel="stylesheet" href="style.css">
<h2>Data Booking</h2>
<table border="1" style="margin:auto; background:#fff; color:#000;">
    <tr>
        <th>User</th><th>Band</th><th>Studio</th><th>Tanggal</th><th>Waktu</th><th>Aksi</th>
    </tr>
    <?php while($row = $bookings->fetch_assoc()): ?>
    <tr>
        <td><?= $row['username'] ?></td>
        <td><?= $row['band_name'] ?></td>
        <td><?= $row['studio'] ?></td>
        <td><?= $row['booking_date'] ?></td>
        <td><?= $row['booking_time'] ?></td>
        <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Hapus booking ini?')">Hapus</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<a href="dashboard.php">Kembali</a>
