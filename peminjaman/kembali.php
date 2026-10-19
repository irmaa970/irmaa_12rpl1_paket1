<?php
session_start();
include '../connection.php';

$db = isset($koneksi) ? $koneksi : (isset($conn) ? $conn : null);

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'peminjam';

// Ambil ID peminjaman dari URL
$id_peminjaman = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($id_peminjaman)) {
    header("Location: tampil.php");
    exit();
}

// Ambil detail data peminjaman
$data = null;
if ($db) {
    $q = mysqli_query($db, "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'");
    if ($q && mysqli_num_rows($q) > 0) {
        $data = mysqli_fetch_assoc($q);
    }
}

// Proses submit pengembalian
if (isset($_POST['submit'])) {
    $tgl_dikembalikan = $_POST['tanggal_dikembalikan'];
    
    // Jika Petugas/Admin, ambil nilai denda dari form. Jika Siswa, set 0.
    $denda = ($role == 'admin' || $role == 'petugas') ? (isset($_POST['denda']) ? $_POST['denda'] : 0) : 0;
    
    $status = 'Dikembalikan';

    if ($db) {
        // Query update pengembalian
        $update = mysqli_query($db, "UPDATE peminjaman SET 
                                    tanggal_dikembalikan = '$tgl_dikembalikan', 
                                    denda = '$denda', 
                                    status = '$status' 
                                    WHERE id_peminjaman = '$id_peminjaman'");

        if ($update) {
            echo "<script>
                    alert('Proses pengembalian berhasil!');
                    window.location.href='tampil.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Gagal memproses pengembalian: " . mysqli_error($db) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pengembalian Alat</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Fredoka', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f0f9ff; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .card-container { background: #ffffff; width: 100%; max-width: 450px; padding: 30px; border-radius: 16px; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.08); border: 1px solid #e0f2fe; }
        .card-container h2 { color: #0369a1; font-size: 22px; margin-bottom: 20px; text-align: center; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; color: #0284c7; font-weight: 600; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px 14px; border: 1.5px solid #bae6fd; border-radius: 10px; font-size: 14px; color: #334155; outline: none; background-color: #f8fafc; }
        .form-group input[readonly] { background-color: #e2e8f0; color: #475569; cursor: not-allowed; }
        .button-group { display: flex; gap: 12px; margin-top: 24px; }
        .btn-submit { flex: 2; background-color: #0284c7; color: #ffffff; border: none; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn-cancel { flex: 1; background-color: #f1f5f9; color: #475569; text-decoration: none; padding: 12px; border-radius: 10px; font-size: 15px; font-weight: 600; text-align: center; }
    </style>
</head>
<body>

    <div class="card-container">
        <h2>Pengembalian Alat</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Peminjam</label>
                <input type="text" value="<?= htmlspecialchars($data['nama_peminjam'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Nama Alat</label>
                <input type="text" value="<?= htmlspecialchars($data['nama_alat'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Pinjam</label>
                <input type="text" value="<?= htmlspecialchars($data['tanggal_pinjam'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Rencana Kembali</label>
                <input type="text" value="<?= htmlspecialchars($data['tanggal_rencana_kembali'] ?? ''); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Dikembalikan Hari Ini</label>
                <input type="date" name="tanggal_dikembalikan" value="<?= date('Y-m-d'); ?>" required>
            </div>

            <!-- DENDA HANYA MUNCUL JIKA USER ADALAH ADMIN / PETUGAS -->
            <?php if ($role == 'admin' || $role == 'petugas') : ?>
                <div class="form-group">
                    <label>Jumlah Denda (Rp)</label>
                    <input type="number" name="denda" value="0" min="0" placeholder="Masukkan denda jika ada">
                </div>
            <?php endif; ?>

            <div class="button-group">
                <button type="submit" name="submit" class="btn-submit">Kembalikan Alat</button>
                <a href="tampil.php" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>