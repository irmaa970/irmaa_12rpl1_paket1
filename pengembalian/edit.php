<?php
session_start();
include '../connection.php';

// Proteksi Login
if (!isset($_SESSION['username']) && !isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$koneksi = $conn ?? $con;
$id_pengembalian = $_GET['id'] ?? 0;

// Ambil data pengembalian yang akan diedit
$query_get = "SELECT g.*, p.nama_peminjam, p.nama_alat 
              FROM pengembalian g 
              JOIN peminjaman p ON g.id_peminjaman = p.id_peminjaman 
              WHERE g.id_pengembalian = '$id_pengembalian'";
$result_get = mysqli_query($koneksi, $query_get);
$data = mysqli_fetch_assoc($result_get);

if (!$data) {
    header("Location: tampil.php");
    exit();
}

// Proses Update Data Kondisi & Denda (Tanpa Mengacak Stok Lagi)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kondisi_kembali   = mysqli_real_escape_string($koneksi, $_POST['kondisi_kembali']);
    $denda             = (float)$_POST['denda'];
    $status_pembayaran = mysqli_real_escape_string($koneksi, $_POST['status_pembayaran']);

    // Update data pengembalian saja
    $query_update = "UPDATE pengembalian SET 
                        kondisi_kembali   = '$kondisi_kembali',
                        denda             = '$denda',
                        status_pembayaran = '$status_pembayaran'
                     WHERE id_pengembalian = '$id_pengembalian'";

    if (mysqli_query($koneksi, $query_update)) {
        header("Location: tampil.php?pesan=update_sukses");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kondisi & Denda</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font Fredoka -->
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Fredoka', sans-serif; 
            background-color: #f0f9ff; 
            color: #334155; 
        }
        .card-custom { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            background: #ffffff; 
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="card card-custom p-4">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fa-regular fa-pen-to-square me-2"></i>Edit Kondisi & Denda
            </h4>
            <p class="text-muted small mb-4">Atur kondisi barang serta besaran denda untuk peminjam.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger rounded-3 py-2 small mb-3"><?= $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- Nama Peminjam -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-1">Nama Peminjam</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($data['nama_peminjam'] ?? '-'); ?>" readonly>
                </div>

                <!-- Nama Alat -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-1">Nama Alat</label>
                    <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($data['nama_alat'] ?? '-'); ?>" readonly>
                </div>

                <!-- Kondisi Alat -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-1">Kondisi Alat</label>
                    <select name="kondisi_kembali" class="form-select" id="kondisi">
                        <?php $k = strtolower($data['kondisi_kembali'] ?? 'baik'); ?>
                        <option value="baik" <?= ($k == 'baik') ? 'selected' : ''; ?>>Baik</option>
                        <option value="terlambat" <?= ($k == 'terlambat') ? 'selected' : ''; ?>>Terlambat</option>
                        <option value="rusak ringan" <?= ($k == 'rusak ringan' || $k == 'rusak_ringan') ? 'selected' : ''; ?>>Rusak Ringan</option>
                        <option value="rusak parah" <?= ($k == 'rusak parah' || $k == 'rusak_parah') ? 'selected' : ''; ?>>Rusak Parah</option>
                        <option value="hilang" <?= ($k == 'hilang') ? 'selected' : ''; ?>>Hilang</option>
                    </select>
                </div>

                <!-- Nominal Denda -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small mb-1">Nominal Denda (Rp)</label>
                    <input type="number" name="denda" class="form-control" value="<?= (float)($data['denda'] ?? 0); ?>" step="500" min="0" required>
                    <div class="form-text text-muted small mt-1">
                        *Denda standar: Terlambat = 10rb/ Hari | Rusak Ringan = 25rb | Rusak Parah = 50rb | Hilang = 100rb
                    </div>
                </div>

                <!-- Status Denda -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small mb-1">Status Denda</label>
                    <select name="status_pembayaran" class="form-select">
                        <?php $sb = $data['status_pembayaran'] ?? 'Belum Dibayar'; ?>
                        <option value="Tidak Ada Denda" <?= ($sb == 'Tidak Ada Denda') ? 'selected' : ''; ?>>Tidak Ada Denda</option>
                        <option value="Belum Dibayar" <?= ($sb == 'Belum Dibayar') ? 'selected' : ''; ?>>Belum Dibayar</option>
                        <option value="Sudah Dibayar" <?= ($sb == 'Sudah Dibayar') ? 'selected' : ''; ?>>Sudah Dibayar</option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between gap-2">
                    <a href="tampil.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>