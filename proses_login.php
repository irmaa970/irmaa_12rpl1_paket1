<?php
session_start();
include 'connection.php';

// Pastikan variabel koneksi tersedia
if (!isset($conn)) {
    die("Error: Variabel \$conn tidak ditemukan di connection.php. Pastikan nama variabel koneksi adalah \$conn.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query pencocokan user berdasarkan database irmaa_12rpl1_paket1
    $query  = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Set session
        $_SESSION['id_user']      = $user['id_user'];
        $_SESSION['username']     = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role']         = $user['role'];

        // Catat aktivitas login ke log_aktivitas
        $id_user   = $user['id_user'];
        $aktivitas = "User " . $user['nama_lengkap'] . " berhasil login";
        mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aktivitas) VALUES ('$id_user', '$aktivitas')");

        header("Location: home.php");
        exit();
    } else {
        header("Location: login.php?pesan=gagal");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>