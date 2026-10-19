<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id_user = $_GET['id'] ?? 0;

if ($id_user > 0) {
    // 1. CEK APABILA USER/PETUGAS MASIH MEMILIKI PEMINJAMAN AKTIF
    $cek_pinjam = mysqli_query($conn, "SELECT * FROM peminjaman 
        WHERE id_user = '$id_user' 
        AND status IN ('Disetujui', 'Dipinjam', 'Menunggu ACC', 'pending', 'menunggu')");

    // Jika masih ada peminjaman yang belum dikembalikan
    if (mysqli_num_rows($cek_pinjam) > 0) {
        header("Location: tampil.php?pesan=gagal_masih_pinjam");
        exit();
    } 

    // 2. JIKA TIDAK ADA PEMINJAMAN, PROSES HAPUS DILANJUTKAN
    $query_hapus = mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id_user'");
    
    if ($query_hapus) {
        header("Location: tampil.php?pesan=berhasil_hapus");
    } else {
        header("Location: tampil.php?pesan=gagal_hapus");
    }
    exit();
}

header("Location: tampil.php");
exit();
?>