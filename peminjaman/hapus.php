<?php
session_start();
include '../connection.php';

$id = $_GET['id'] ?? 0;

if ($id > 0) {
    $query = "DELETE FROM peminjaman WHERE id_peminjaman = '$id'";
    mysqli_query($conn, $query);
}

header("Location: tampil.php");
exit();
?>