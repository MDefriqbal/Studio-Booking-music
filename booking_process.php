<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token tidak valid.");
    }

    $band_name = $_POST['band_name'];
    $date = $_POST['booking_date'];
    $time = $_POST['booking_time'];
    $studio_id = $_POST['studio_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, studio_id, band_name, booking_date, booking_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $user_id, $studio_id, $band_name, $date, $time);
    $stmt->execute();

    header("Location: dashboard.php");
}
?>