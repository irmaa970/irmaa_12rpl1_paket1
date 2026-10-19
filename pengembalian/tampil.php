<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username']) && !isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'] ?? 0;

// Ambil data role dari session
$raw_role = $_SESSION['role'] ?? $_SESSION['level'] ?? '';
$role     = strtolower(trim($raw_role));

if (strpos($role, 'admin') !== false) {
    $user_type = 'admin';
} elseif (strpos($role, 'petugas') !== false) {
    $user_type = 'petugas';
} else {
    $user_type = 'siswa';
}

$koneksi = $conn ?? $con;

// PROSES KONFIRMASI PENGEMBALIAN OLEH PETUGAS / ADMIN (TAMBAH STOK DISINI)
if (isset($_POST['konfirmasi_kembali']) && ($user_type == 'admin' || $user_type == 'petugas')) {
    $id_pengembalian = $_POST['id_pengembalian'] ?? 0;
    $id_peminjaman   = $_POST['id_peminjaman'] ?? 0;

    if ($id_peminjaman > 0) {
        mysqli_begin_transaction($koneksi);

        try {
            // 1. Ubah status peminjaman menjadi 'dikembalikan'
            $q_update_p = "UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = '$id_peminjaman'";
            if (!mysqli_query($koneksi, $q_update_p)) {
                throw new Exception("Gagal mengupdate status peminjaman.");
            }

            // 2. Ambil detail barang dari detail_peminjaman untuk update stok alat
            $q_detail = "SELECT id_alat, jumlah FROM detail_peminjaman WHERE id_peminjaman = '$id_peminjaman'";
            $res_detail = mysqli_query($koneksi, $q_detail);

            if ($res_detail && mysqli_num_rows($res_detail) > 0) {
                while ($row_d = mysqli_fetch_assoc($res_detail)) {
                    $id_alat = $row_d['id_alat'];
                    $jumlah  = $row_d['jumlah'];

                    // 3. Tambah stok alat secara otomatis
                    $q_stok = "UPDATE alat SET stok = stok + $jumlah WHERE id_alat = '$id_alat'";
                    if (!mysqli_query($koneksi, $q_stok)) {
                        throw new Exception("Gagal menambah stok alat.");
                    }
                }
            }

            mysqli_commit($koneksi);
            header("Location: tampil.php?pesan=konfirmasi_sukses");
            exit();

        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location='tampil.php';</script>";
            exit();
        }
    }
}

// QUERY JOIN KE TABEL DETAIL_PEMINJAMAN
if ($user_type == 'admin' || $user_type == 'petugas') {
    // Admin & Petugas: Tampilkan semua data pengembalian
    $query = "SELECT g.*, 
                     p.nama_peminjam, 
                     p.nama_alat, 
                     p.tanggal_pinjam AS tgl_pinjam,
                     p.status AS status_pinjam,
                     dp.*
              FROM pengembalian g
              JOIN peminjaman p ON g.id_peminjaman = p.id_peminjaman
              LEFT JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
              ORDER BY g.id_pengembalian DESC";
} else {
    // Siswa: Tampilkan data pengembalian milik siswa yang login saja
    $query = "SELECT g.*, 
                     p.nama_peminjam, 
                     p.nama_alat, 
                     p.tanggal_pinjam AS tgl_pinjam,
                     p.status AS status_pinjam,
                     dp.*
              FROM pengembalian g
              JOIN peminjaman p ON g.id_peminjaman = p.id_peminjaman
              LEFT JOIN detail_peminjaman dp ON p.id_peminjaman = dp.id_peminjaman
              WHERE p.id_user = '$id_user'
              ORDER BY g.id_pengembalian DESC";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Pengembalian</title>
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
        }
        .table thead th {
            background-color: #e0f2fe;
            color: #0284c7;
            border: none;
            padding: 12px 16px;
        }
        .table tbody td {
            vertical-align: middle;
            padding: 12px 16px;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-1">
                    <i class="fa-solid fa-rotate-left me-2"></i>Kelola Data Pengembalian
                </h3>
                <p class="text-muted small mb-0">Riwayat pengembalian barang, pemeriksaan kondisi, dan status denda.</p>
            </div>
            <div class="d-flex gap-2">
                <!-- Tombol Ajukan Pengembalian: Tampil jika pengguna adalah Siswa -->
                <?php if ($user_type == 'siswa'): ?>
                    <a href="tambah.php" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Pengembalian
                    </a>
                <?php endif; ?>

                <!-- Tombol Home -->
                <a href="../home.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Home
                </a>
            </div>
        </div>

        <!-- Alert Notifikasi -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'pengajuan_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 fw-semibold mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Pengajuan pengembalian berhasil dikirim! Silakan serahkan alat ke petugas.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'konfirmasi_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 fw-semibold mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Pengembalian berhasil dikonfirmasi dan stok barang telah bertambah!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'bayar_sukses'): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 fw-semibold mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Pembayaran denda berhasil dikonfirmasi!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Tabel Data -->
        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Peminjam</th>
                            <th>Nama Alat</th>
                            <th class="text-center">Jumlah Unit</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Dikembalikan</th>
                            <th>Kondisi Alat</th>
                            <th>Status Transaksi</th>
                            <th>Denda</th>
                            <?php if ($user_type == 'petugas' || $user_type == 'admin'): ?>
                                <th class="text-center">Aksi</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                <?php
                                    $jumlah_unit = $row['jumlah'] 
                                                ?? $row['jumlah_pinjam'] 
                                                ?? $row['jumlah_alat'] 
                                                ?? $row['jumlah_unit'] 
                                                ?? $row['qty'] 
                                                ?? $row['unit'] 
                                                ?? 1;
                                    $status_pinjam = strtolower($row['status_pinjam'] ?? '');
                                ?>
                                <tr>
                                    <td class="text-center fw-semibold"><?= $no++; ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                                    <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1 fw-bold">
                                            <?= htmlspecialchars($jumlah_unit); ?> Unit
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['tgl_pinjam'] ?? '-'); ?></td>
                                    <td>
                                        <?= htmlspecialchars($row['tgl_pengembalian'] ?? $row['tgl_kembali'] ?? $row['tanggal_kembali'] ?? '-'); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $kondisi = strtolower($row['kondisi_kembali'] ?? 'baik');
                                            $badgeClass = ($kondisi == 'terlambat' || $kondisi == 'rusak') ? 'bg-warning-subtle text-warning border-warning-subtle' : 'bg-success-subtle text-success border-success-subtle';
                                        ?>
                                        <span class="badge <?= $badgeClass; ?> border rounded-pill px-3 py-1">
                                            <?= ucfirst($kondisi); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($status_pinjam == 'dikembalikan'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold">
                                                Dikembalikan
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fw-bold">
                                                Proses Pengembalian
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $denda = (float)($row['denda'] ?? 0);
                                            $status_bayar = $row['status_pembayaran'] ?? 'Belum Dibayar';
                                        ?>
                                        <span class="fw-bold <?= ($denda > 0 && $status_bayar != 'Sudah Dibayar') ? 'text-danger' : 'text-success'; ?>">
                                            <?= ($denda > 0) ? 'Rp ' . number_format($denda, 0, ',', '.') : 'Lunas / Tidak Ada'; ?>
                                        </span>
                                        <?php if ($denda > 0 && $status_bayar == 'Sudah Dibayar'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill ms-1">Lunas</span>
                                        <?php endif; ?>
                                    </td>

                                    <?php if ($user_type == 'petugas' || $user_type == 'admin'): ?>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <!-- Tombol Konfirmasi Pengembalian TANPA LOGO CENTANG -->
                                                <?php if ($status_pinjam != 'dikembalikan'): ?>
                                                    <form action="" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi pengembalian barang ini? Stok alat akan bertambah secara otomatis.');">
                                                        <input type="hidden" name="id_pengembalian" value="<?= $row['id_pengembalian']; ?>">
                                                        <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>">
                                                        <button type="submit" name="konfirmasi_kembali" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold">
                                                            Dikembalikan
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <a href="edit.php?id=<?= $row['id_pengembalian']; ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold">
                                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                </a>

                                                <?php if ($denda > 0 && $status_bayar != 'Sudah Dibayar'): ?>
                                                    <a href="bayar.php?id=<?= $row['id_pengembalian']; ?>" 
                                                       class="btn btn-success btn-sm rounded-pill px-3 fw-semibold" 
                                                       onclick="return confirm('Apakah denda sebesar Rp <?= number_format($denda, 0, ',', '.'); ?> sudah dibayar oleh siswa?')">
                                                        <i class="fa-solid fa-money-bill-wave me-1"></i> Bayar
                                                    </a>
                                                <?php endif; ?>

                                                <?php if ($user_type == 'admin'): ?>
                                                    <a href="hapus.php?id=<?= $row['id_pengembalian']; ?>" 
                                                       class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold" 
                                                       onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?= ($user_type == 'petugas' || $user_type == 'admin') ? '10' : '9'; ?>" class="text-center py-4 text-muted">Belum ada riwayat pengembalian.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>