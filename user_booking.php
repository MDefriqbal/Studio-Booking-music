<?php
session_start();
include 'config.php';

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_GET['studio_id'])) {
    header("Location: studio_list.php"); // redirect jika belum pilih studio
    exit;
}

$studio_id = $_GET['studio_id'];
$stmt = $mysqli->prepare("SELECT * FROM studios WHERE id = ?");
$stmt->bind_param("i", $studio_id);
$stmt->execute();
$result = $stmt->get_result();
$studio = $result->fetch_assoc();

if (!$studio) {
    die("Studio tidak ditemukan");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $band_name = $_POST['band_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $cek = $mysqli->prepare("SELECT * FROM bookings WHERE studio_id=? AND booking_date=? AND booking_time=?");
    $cek->bind_param("iss", $studio_id, $date, $time);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $msg = "<p style='color:red;'>Studio sudah dibooking di waktu tersebut!</p>";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO bookings (user_id, studio_id, band_name, booking_date, booking_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $studio_id, $band_name, $date, $time);
        $stmt->execute();
        $msg = "<p style='color:lightgreen;'>Booking berhasil!</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Booking Studio</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('Kumpulan Gambar/tampilan user.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .booking-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            width: 350px;
            color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.6);
        }

        .booking-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .studio-info {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 6px;
            background-color: rgba(255,255,255,0.1);
        }

        .studio-info strong {
            display: block;
            font-size: 18px;
            margin-bottom: 5px;
        }

        .booking-box input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
            background-color: #f1f1f1;
            color: #000;
        }

        .booking-box button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .booking-box button:hover {
            background-color: #388e3c;
        }

        .dashboard-link {
            display: block;
            margin-top: 15px;
            text-align: center;
        }

        .dashboard-link a {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
        }

        .dashboard-link a:hover {
            background-color: #0056b3;
        }

        .change-studio {
            margin-top: 8px;
            display: inline-block;
            background-color: orange;
            padding: 6px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }

        .change-studio:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>
    <div class="booking-box">
        <h2>Booking Studio</h2>
        <?= $msg ?? '' ?>

        <!-- Studio Info -->
        <div class="studio-info">
            <strong><?= $studio['name'] ?></strong>
            <?= $studio['location'] ?><br>
            Rp<?= number_format($studio['price'], 0, ',', '.') ?>
            <br>
            <a href="studio_list.php" class="change-studio">← Ganti Studio</a>
        </div>

        <form method="post">
            <input type="hidden" name="studio_id" value="<?= $studio['id'] ?>">
            <input type="text" name="band_name" placeholder="Nama Band" required>
            <input type="date" name="date" required>
            <input type="time" name="time" required>
            <button type="submit">Booking</button>
        </form>

        <div class="dashboard-link">
            <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>
