<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "studio_booking"; // ganti jika nama database Anda berbeda

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
