<?php
session_start();
include 'config.php';

// Pastikan pengguna sudah login dan memiliki peran 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = ''; // Untuk menyimpan pesan feedback

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? ''; // Ambil nilai action (delete_single atau delete_all)

    if ($action === 'delete_single') {
        $booking_id = $_POST['booking_id'] ?? 0;

        // Validasi dan pastikan booking_id adalah integer
        if (filter_var($booking_id, FILTER_VALIDATE_INT) && $booking_id > 0) {
            // Hapus booking hanya jika itu milik user yang sedang login
            $stmt = $mysqli->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $booking_id, $user_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "Booking berhasil dihapus.";
                } else {
                    $message = "Booking tidak ditemukan atau Anda tidak memiliki izin untuk menghapusnya.";
                }
            } else {
                $message = "Gagal menghapus booking: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "ID Booking tidak valid.";
        }
    } elseif ($action === 'delete_all') {
        // Hapus semua booking milik user yang sedang login
        $stmt = $mysqli->prepare("DELETE FROM bookings WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "Semua riwayat booking Anda berhasil dihapus.";
            } else {
                $message = "Tidak ada riwayat booking untuk dihapus.";
            }
        } else {
            $message = "Gagal menghapus semua booking: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Aksi tidak valid.";
    }
} else {
    $message = "Permintaan tidak valid.";
}

// Simpan pesan ke session dan redirect kembali ke halaman riwayat booking
$_SESSION['delete_message'] = $message;
header("Location: booking_history.php");
exit;
?>