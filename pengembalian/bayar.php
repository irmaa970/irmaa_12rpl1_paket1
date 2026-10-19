<?php
session_start();
include "../connection.php";

// Deteksi variabel koneksi ($conn atau $con)
$koneksi = isset($conn) ? $conn : (isset($con) ? $con : null);

if (!$koneksi) {
    die("Koneksi database gagal. Cek file connection.php Anda.");
}

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// LOGIKA PROSES BAYAR
if (isset($_GET['id'])) {
    $id_bayar = intval($_GET['id']);
    
    // Update status pembayaran di database
    $update_sql = "UPDATE pengembalian 
                   SET status_pembayaran = 'Sudah Dibayar' 
                   WHERE id_pengembalian = '$id_bayar'";
    
    if ($koneksi->query($update_sql)) {
        // Redirect kembali ke halaman tampil.php setelah berhasil diubah
        header("Location: tampil.php?pesan=bayar_sukses");
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui pembayaran: " . $koneksi->error . "'); window.location.href='tampil.php';</script>";
        exit;
    }
} else {
    header("Location: tampil.php");
    exit;
}
?>