<?php
session_start();
include 'config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<form action="booking_process.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input name="band_name" placeholder="Nama Band" required>
    <input name="booking_date" type="date" required>
    <input name="booking_time" type="time" required>
    <select name="studio_id" required>
        <option value="">Pilih Studio</option>
        <?php
        $result = $mysqli->query("SELECT id, name FROM studios");
        while ($studio = $result->fetch_assoc()) {
            $id = htmlspecialchars($studio['id'], ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($studio['name'], ENT_QUOTES, 'UTF-8');
            echo "<option value='{$id}'>{$name}</option>";
        }
        ?>
    </select>
    <button type="submit">Booking</button>
</form>
