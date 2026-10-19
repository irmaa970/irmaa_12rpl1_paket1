<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$role    = strtolower($_SESSION['role'] ?? 'peminjam');
$id_user = $_SESSION['id_user'] ?? 0;

// PROSES SIMPAN FORM PENGEMBALIAN OLEH SISWA (STOK BELUM BERTAMBAH)
if (isset($_POST['simpan_pengembalian'])) {
    $id_peminjaman   = $_POST['id_peminjaman'] ?? 0;
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? date('Y-m-d');
    $kondisi_kembali = $_POST['kondisi_kembali'] ?? 'baik';
    
    // Nilai awal denda = 0 & status denda otomatis 'menunggu diperiksa'
    $denda        = 0;
    $status_denda = 'menunggu diperiksa';

    if ($id_peminjaman > 0) {
        // Mulai Transaksi Database untuk menjaga konsistensi data
        mysqli_begin_transaction($conn);

        try {
            // 1. Insert data ke tabel pengembalian
            $query_insert = "INSERT INTO pengembalian (id_peminjaman, tanggal_kembali, kondisi_kembali, denda, status_denda) 
                             VALUES ('$id_peminjaman', '$tanggal_kembali', '$kondisi_kembali', '$denda', '$status_denda')";
            if (!mysqli_query($conn, $query_insert)) {
                throw new Exception("Gagal menyimpan data pengembalian.");
            }

            // 2. Ubah status di tabel peminjaman menjadi 'proses pengembalian' (Stok belum bertambah)
            $query_update_peminjaman = "UPDATE peminjaman SET status = 'proses pengembalian' WHERE id_peminjaman = '$id_peminjaman'";
            if (!mysqli_query($conn, $query_update_peminjaman)) {
                throw new Exception("Gagal memperbarui status peminjaman.");
            }

            // Catatan: Penambahan stok alat (UPDATE alat SET stok = stok + jumlah) 
            // dipindahkan ke kelola pengembalian (tampil.php) saat petugas menekan tombol konfirmasi.

            // Commit transaksi jika semua query sukses
            mysqli_commit($conn);
            header("Location: tampil.php?pesan=pengajuan_sukses");
            exit();

        } catch (Exception $e) {
            // Rollback jika terjadi kegagalan
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
        }
    }
}

// Ambil daftar peminjaman aktif milik user
if ($role == 'admin' || $role == 'petugas') {
    $q_pinjam = "SELECT * FROM peminjaman WHERE LOWER(status) NOT IN ('dikembalikan', 'proses pengembalian')";
} else {
    $q_pinjam = "SELECT * FROM peminjaman WHERE id_user = '$id_user' AND LOWER(status) NOT IN ('dikembalikan', 'proses pengembalian')";
}
$data_pinjam = mysqli_query($conn, $q_pinjam);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengembalian Barang</title>
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); 
            background: #ffffff; 
        }
        .form-label {
            font-weight: 600;
        }
        .btn-primary {
            background-color: #0284c7;
            border-color: #0284c7;
        }
        .btn-primary:hover {
            background-color: #0369a1;
            border-color: #0369a1;
        }
    </style>
</head>
<body>

    <div class="container my-5" style="max-width: 650px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-primary mb-0">
                <i class="fa-solid fa-file-circle-plus me-2"></i>Ajukan Pengembalian
            </h3>
            <a href="tampil.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card card-custom p-4">
            <form action="" method="POST">
                <div class="mb-3">
                    <label class="form-label">Pilih Barang yang Dipinjam</label>
                    <select name="id_peminjaman" class="form-select rounded-3" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php if ($data_pinjam && mysqli_num_rows($data_pinjam) > 0): ?>
                            <?php while ($dp = mysqli_fetch_assoc($data_pinjam)): ?>
                                <option value="<?= $dp['id_peminjaman']; ?>">
                                    <?= htmlspecialchars($dp['nama_alat'] ?? 'Alat'); ?> (Pinjam: <?= $dp['tanggal_pinjam'] ?? '-'; ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada barang yang sedang dipinjam</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Pengembalian</label>
                    <input type="date" name="tanggal_kembali" class="form-control rounded-3" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kondisi Barang Saat Dikembalikan</label>
                    <select name="kondisi_kembali" class="form-select rounded-3" required>
                        <option value="baik">Baik</option>
                        <option value="rusak ringan">Rusak Ringan</option>
                        <option value="rusak parah">Rusak Parah</option>
                        <option value="hilang">Hilang</option>
                    </select>
                    <small class="text-muted mt-1 d-block">
                        *Petugas akan memverifikasi fisik barang dan menentukan denda jika ada.
                    </small>
                </div>

                <button type="submit" name="simpan_pengembalian" class="btn btn-primary rounded-pill w-100 fw-semibold py-2">
                    <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Pengembalian
                </button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>