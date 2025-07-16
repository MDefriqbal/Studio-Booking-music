<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID booking tidak ditemukan.";
    exit;
}

$booking_id = intval($_GET['id']);

// Verifikasi booking milik user
$stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Booking tidak ditemukan atau bukan milik Anda.";
    exit;
}

// Lakukan pembatalan
$mysqli->query("UPDATE bookings SET status='canceled' WHERE id=$booking_id");

echo "<p style='color:green;'>Booking berhasil dibatalkan.</p>";
echo "<a href='booking_history.php'>← Kembali ke Riwayat</a>";
?>
