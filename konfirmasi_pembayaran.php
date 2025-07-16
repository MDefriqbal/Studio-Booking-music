<?php
include 'config.php'; // Pastikan file koneksi database Anda terhubung

// Memulai session, penting jika Anda ingin menyimpan data sementara untuk ditampilkan di struk
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['id']) || !isset($_POST['metode'])) {
        die("ID Booking atau metode pembayaran tidak ditemukan.");
    }

    $id_booking = $_POST['id'];
    $metode_pembayaran = $_POST['metode'];

    // --- PROSES UPDATE STATUS PEMBAYARAN DI DATABASE ---
    // Contoh: Update kolom payment_status di tabel bookings
    $update_query = $mysqli->prepare("UPDATE bookings SET payment_status = 'paid', payment_method = ? WHERE id = ?");
    $update_query->bind_param("si", $metode_pembayaran, $id_booking);

    if ($update_query->execute()) {
        // Jika update berhasil, Anda bisa mengambil data booking lagi
        // untuk ditampilkan di struk atau menyimpannya di session
        $select_booking = $mysqli->prepare("
            SELECT b.*, s.name AS studio_name, s.price
            FROM bookings b
            JOIN studios s ON b.studio_id = s.id
            WHERE b.id = ?
        ");
        $select_booking->bind_param("i", $id_booking);
        $select_booking->execute();
        $result_booking = $select_booking->get_result();
        $data_booking = $result_booking->fetch_assoc();

        if ($data_booking) {
            // Simpan data yang diperlukan di session untuk halaman struk.php
            // Ini akan membantu mengatasi 'Undefined variable' di struk.php
            $_SESSION['struk_data'] = [
                'no_reservasi' => $data_booking['id'], // atau nomor reservasi lain jika ada
                'nama_band' => $data_booking['band_name'],
                'tanggal' => $data_booking['booking_date'],
                'jam' => $data_booking['booking_time'],
                'studio' => $data_booking['studio_name'],
                'total_bayar' => $data_booking['price'],
                'metode_bayar' => $data_booking['payment_method']
                // Tambahkan data lain yang mungkin dibutuhkan di struk
            ];

            // Redirect ke halaman struk.php
            // Perhatikan path URL-nya. Jika struk.php ada di direktori yang sama:
            header("Location: struk.php");
            // Jika struk.php ada di folder 'reservasi', misalnya:
            // header("Location: reservasi/struk.php");
            // Sesuai screenshot Anda, sepertinya di 'reservasi_fotobox', jadi:
            // header("Location: /reservasi_fotobox/struk.php"); // Ini akan mengarah ke root server
            // Atau jika struk.php ada di folder studio_booking:
            // header("Location: struk.php");
            exit; // Penting untuk menghentikan eksekusi script setelah redirect
        } else {
            echo "Data booking tidak ditemukan setelah pembayaran.";
        }
    } else {
        echo "Gagal mengupdate status pembayaran: " . $mysqli->error;
    }

    $update_query->close();
} else {
    // Jika diakses langsung tanpa POST, arahkan ke halaman utama atau tampilkan error
    header("Location: index.php"); // Atau halaman lain yang sesuai
    exit;
}

$mysqli->close();
?>