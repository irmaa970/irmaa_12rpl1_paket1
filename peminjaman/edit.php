<?php
session_start();
include '../connection.php';

// Cek status login
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    header('Location: ../login.php');
    exit();
}

$id_peminjaman = $_GET['id'] ?? '';

if (empty($id_peminjaman)) {
    header('Location: tampil.php');
    exit();
}

// Ambil data peminjaman saat ini
$query_peminjaman = mysqli_query($conn, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
$data_peminjaman = mysqli_fetch_assoc($query_peminjaman);

if (!$data_peminjaman) {
    echo "<script>alert('Data peminjaman tidak ditemukan!'); window.location='tampil.php';</script>";
    exit();
}

// Proses Update Status Peminjaman
if (isset($_POST['update'])) {
    $status_baru = $_POST['status'];
    $status_lama = $data_peminjaman['status'];

    mysqli_begin_transaction($conn);

    try {
        // 1. Jika status berubah dari selain 'Disetujui' menjadi 'Disetujui', kurangi stok alat
        if ($status_lama != 'Disetujui' && $status_baru == 'Disetujui') {
            $q_detail = mysqli_query($conn, "SELECT d.id_alat, d.jumlah, a.nama_alat, a.stok 
                                             FROM detail_peminjaman d 
                                             JOIN alat a ON d.id_alat = a.id_alat 
                                             WHERE d.id_peminjaman = '$id_peminjaman'");

            while ($row = mysqli_fetch_assoc($q_detail)) {
                if ($row['stok'] < $row['jumlah']) {
                    throw new Exception("Stok alat '" . $row['nama_alat'] . "' tidak mencukupi (Sisa: " . $row['stok'] . ")");
                }
                
                // Kurangi stok alat
                $id_alat = $row['id_alat'];
                $jumlah  = $row['jumlah'];
                mysqli_query($conn, "UPDATE alat SET stok = stok - $jumlah WHERE id_alat = '$id_alat'");
            }
        } 
        // 2. Jika tadinya 'Disetujui' lalu dibatalkan/ditolak, kembalikan stok alat
        elseif ($status_lama == 'Disetujui' && $status_baru != 'Disetujui' && $status_baru != 'Dikembalikan') {
            $q_detail = mysqli_query($conn, "SELECT id_alat, jumlah FROM detail_peminjaman WHERE id_peminjaman = '$id_peminjaman'");
            while ($row = mysqli_fetch_assoc($q_detail)) {
                $id_alat = $row['id_alat'];
                $jumlah  = $row['jumlah'];
                mysqli_query($conn, "UPDATE alat SET stok = stok + $jumlah WHERE id_alat = '$id_alat'");
            }
        }

        // Update status pada tabel peminjaman
        mysqli_query($conn, "UPDATE peminjaman SET status = '$status_baru' WHERE id_peminjaman = '$id_peminjaman'");

        mysqli_commit($conn);
        echo "<script>alert('Status peminjaman berhasil diperbarui!'); window.location='tampil.php';</script>";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Gagal memproses: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Status Peminjaman</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>
    <div class="container">
        <h2>Edit Status Peminjaman</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>ID Peminjaman</label>
                <input type="text" value="<?= $data_peminjaman['id_peminjaman']; ?>" disabled>
            </div>
            <div class="form-group">
                <label>Status Peminjaman</label>
                <select name="status" required>
                    <option value="Menunggu" <?= $data_peminjaman['status'] == 'Menunggu' ? 'selected' : ''; ?>>Menunggu</option>
                    <option value="Disetujui" <?= $data_peminjaman['status'] == 'Disetujui' ? 'selected' : ''; ?>>Disetujui</option>
                    <option value="Ditolak" <?= $data_peminjaman['status'] == 'Ditolak' ? 'selected' : ''; ?>>Ditolak</option>
                    <option value="Dikembalikan" <?= $data_peminjaman['status'] == 'Dikembalikan' ? 'selected' : ''; ?>>Dikembalikan</option>
                </select>
            </div>
            <button type="submit" name="update" class="btn">Simpan Perubahan</button>
            <a href="tampil.php" class="btn-back">Batal</a>
        </form>
    </div>
</body>
</html>