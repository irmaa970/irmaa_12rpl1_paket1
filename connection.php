<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "irmaa_12rpl1_paket1"; // Nama database sesuai dump SQL kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>