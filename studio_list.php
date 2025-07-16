<?php
session_start();
include 'config.php';

$result = $mysqli->query("SELECT * FROM studios");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pilih Studio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('Kumpulan Gambar/Backround Studio.jpg') no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            padding: 30px;
            margin: 0;
        }

        h2 {
            margin-bottom: 30px;
            color: white;
            text-shadow: 2px 2px 4px black;
        }

        .studio-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .studio-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 250px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            color: white;
        }

        .studio-card:hover {
            transform: scale(1.05);
        }

        .studio-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
        }

        .studio-card h3,
        .studio-card p {
            margin: 10px 0;
        }

        .studio-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .studio-card a:hover {
            background: #2e7d32;
        }

        .dashboard-link {
            margin-top: 50px;
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
    </style>
</head>
<body>

    <h2>Pilih Studio Musik</h2>

    <div class="studio-cards">
        <?php
        $images = [
            "Kumpulan Gambar/Studio 1.jpg",
            "Kumpulan Gambar/Studio 2.jpg",
            "Kumpulan Gambar/Studio 3.jpg"
        ];
        $index = 0;
        while ($row = $result->fetch_assoc()) :
            $imagePath = isset($images[$index]) ? $images[$index] : "uploads/default.jpg";
        ?>
            <div class="studio-card">
                <img src="<?= $imagePath ?>" alt="<?= $row['name'] ?>">
                <h3><?= $row['name'] ?></h3>
                <p><?= $row['location'] ?></p>
                <p><strong>Rp <?= number_format($row['price'], 0, ',', '.') ?></strong></p>
                <a href="user_booking.php?studio_id=<?= $row['id'] ?>">Pilih Studio</a>
            </div>
        <?php
            $index++;
        endwhile;
        ?>
    </div>

    <div class="dashboard-link">
        <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
    </div>

</body>
</html>
