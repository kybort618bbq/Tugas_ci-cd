<?php
$host     = "localhost";   // host server
$user     = "root";        // user default XAMPP
$pass     = "";            // password default kosong (XAMPP)
$db       = "db_login1";   // nama database

try {
    // koneksi pakai PDO
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // set mode error biar gampang debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // kalau berhasil konek, bisa tampilkan pesan
    echo " ";
} catch (PDOException $e) {
    // kalau gagal konek
    die("Koneksi gagal: " . $e->getMessage());
}
?>
