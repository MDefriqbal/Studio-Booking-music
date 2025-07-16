<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $metode = $_POST['metode'];

    $query = $mysqli->prepare("UPDATE bookings SET payment_status = 'paid', payment_method = ? WHERE id = ?");
    $query->bind_param("si", $metode, $id);

    if ($query->execute()) {
        header("Location: struk.php?id=" . $id);
    } else {
        echo "Gagal memproses pembayaran.";
    }
}
?>
