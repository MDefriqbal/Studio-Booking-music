<?php
$mysqli = new mysqli("localhost", "root", "", "studio_booking");
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
?>
