-- Buat database
CREATE DATABASE IF NOT EXISTS studio_booking;
USE studio_booking;

-- Tabel pengguna
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Tabel studio
CREATE TABLE IF NOT EXISTS studios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255),
    price DECIMAL(10,2)
);

-- Tabel booking
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    studio_id INT,
    band_name VARCHAR(100),
    booking_date DATE,
    booking_time TIME,
    payment_status ENUM('pending', 'paid') DEFAULT 'pending',
    payment_method VARCHAR(50),
    bukti_pembayaran VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (studio_id) REFERENCES studios(id)
);

-- Data awal studio
INSERT INTO studios (name, location, price) VALUES
('Studio 1', 'Jl. Musik No.1', 100000),
('Studio 2', 'Jl. Musik No.2', 120000),
('Studio 3', 'Jl. Musik No.3', 150000);
