<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$role = strtolower($_SESSION['role'] ?? $_SESSION['level'] ?? 'peminjam');

// Redirect jika akun peminjam/siswa
if ($role == 'peminjam' || $role == 'siswa') {
    header("Location: saya.php");
    exit();
}

// PROSES SETUJU / TOLAK (Untuk Petugas)

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id_pinjam = intval($_GET['id']);
    $aksi      = strtolower(trim($_GET['aksi']));

    if ($aksi == 'setuju') {
        $status_baru = 'Disetujui';
        $pesan       = 'disetujui';
        
        // =========================================================================
        // TAMBAHAN: KURANGI STOK ALAT SAAT PEMINJAMAN DISETUJUI
        // =========================================================================
        $query_detail = "SELECT id_alat, jumlah FROM detail_peminjaman WHERE id_peminjaman = '$id_pinjam'";
        $res_detail   = mysqli_query($conn, $query_detail);

        if ($res_detail) {
            while ($row_detail = mysqli_fetch_assoc($res_detail)) {
                $id_alat_pinjam = $row_detail['id_alat'];
                $jumlah_pinjam  = (int)$row_detail['jumlah'];

                if ($id_alat_pinjam > 0) {
                    mysqli_query($conn, "UPDATE alat SET stok = stok - $jumlah_pinjam WHERE id_alat = '$id_alat_pinjam'");
                }
            }
        }
        // =========================================================================

    } elseif ($aksi == 'tolak') {
        $status_baru = 'Ditolak';
        $pesan       = 'ditolak';
    }

    if (isset($status_baru)) {
        mysqli_query($conn, "UPDATE peminjaman SET status = '$status_baru' WHERE id_peminjaman = '$id_pinjam'");
        header("Location: " . $_SERVER['PHP_SELF'] . "?pesan=" . $pesan);
        exit();
    }
}


// PROSES HAPUS (Khusus Admin)

if (isset($_GET['hapus_id']) && $role == 'admin') {
    $id_hapus = intval($_GET['hapus_id']);
    
    // 1. Hapus data di tabel pengembalian terlebih dahulu (karena ada FK id_peminjaman)
    mysqli_query($conn, "DELETE FROM pengembalian WHERE id_peminjaman = '$id_hapus'");
    
    // 2. Hapus data di tabel detail_peminjaman
    mysqli_query($conn, "DELETE FROM detail_peminjaman WHERE id_peminjaman = '$id_hapus'");
    
    // 3. Hapus data utama di tabel peminjaman
    $delete = mysqli_query($conn, "DELETE FROM peminjaman WHERE id_peminjaman = '$id_hapus'");
    
    if ($delete) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?pesan=terhapus");
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?pesan=gagal_hapus");
    }
    exit();
}

// QUERY TAMPIL DATA

$query  = "SELECT p.*, d.jumlah AS qty_detail 
           FROM peminjaman p 
           LEFT JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
           ORDER BY p.id_peminjaman DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Peminjaman</title>
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
        .table-custom th { 
            background-color: #e0f2fe; 
            color: #0284c7; 
            font-weight: 600; 
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-primary mb-1">
                    <i class="fa-solid fa-boxes-packing me-2"></i>Kelola Data Peminjaman
                </h3>
                <p class="text-muted small mb-0">Daftar permohonan peminjaman alat dan kelola datanya.</p>
            </div>
            <div>
                <a href="../home.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Home
                </a>
            </div>
        </div>

        <!-- Alert Notifikasi -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'disetujui'): ?>
            <div class="alert alert-success rounded-3 fw-semibold mb-4">
                <i class="fa-solid fa-circle-check me-2"></i> Peminjaman berhasil disetujui!
            </div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'ditolak'): ?>
            <div class="alert alert-danger rounded-3 fw-semibold mb-4">
                <i class="fa-solid fa-circle-xmark me-2"></i> Peminjaman telah ditolak.
            </div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'terhapus'): ?>
            <div class="alert alert-success rounded-3 fw-semibold mb-4">
                <i class="fa-solid fa-circle-check me-2"></i> Data peminjaman berhasil dihapus!
            </div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal_hapus'): ?>
            <div class="alert alert-danger rounded-3 fw-semibold mb-4">
                <i class="fa-solid fa-circle-xmark me-2"></i> Gagal menghapus data peminjaman.
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peminjam</th>
                            <th>Nama Alat</th>
                            <th>Jumlah</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Rencana Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($result && mysqli_num_rows($result) > 0):
                            while ($row = mysqli_fetch_assoc($result)): 
                                $status_raw = $row['status'] ?? '';
                                $status     = strtolower(trim($status_raw));
                                
                                $jumlah      = !empty($row['qty_detail']) ? $row['qty_detail'] : ($row['jumlah'] ?? $row['jumlah_pinjam'] ?? 1);
                                $tgl_pinjam  = $row['tanggal_pinjam'] ?? $row['tgl_pinjam'] ?? '-';
                                $tgl_rencana = $row['tanggal_kembali'] 
                                            ?? $row['tanggal_rencana_kembali'] 
                                            ?? $row['tgl_rencana_kembali'] 
                                            ?? $row['tanggal_rencana_pinjam'] 
                                            ?? $row['tgl_kembali'] 
                                            ?? '-';

                                $is_menunggu = (empty($status) || strpos($status, 'menunggu') !== false || strpos($status, 'pending') !== false || $status == 'acc');
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama_peminjam'] ?? $row['nama_user'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($jumlah); ?> unit</td>
                                <td><?= htmlspecialchars($tgl_pinjam); ?></td>
                                <td><?= htmlspecialchars($tgl_rencana); ?></td>
                                <td>
                                    <?php if ($status == 'disetujui' || $status == 'dipinjam'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">Disetujui</span>
                                    <?php elseif ($status == 'ditolak'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Ditolak</span>
                                    <?php elseif (strpos($status, 'kembali') !== false): ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">Dikembalikan</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">Menunggu ACC</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($role == 'admin'): ?>
                                        <!-- AKSI UNTUK ADMIN: EDIT & HAPUS -->
                                        <a href="edit.php?id=<?= $row['id_peminjaman']; ?>" class="btn btn-warning btn-sm text-white rounded-pill px-3 fw-semibold me-1">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                        </a>
                                        <a href="<?= $_SERVER['PHP_SELF']; ?>?hapus_id=<?= $row['id_peminjaman']; ?>" 
                                           class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fa-solid fa-trash me-1"></i> Hapus
                                        </a>

                                    <?php else: ?>
                                        <!-- AKSI UNTUK PETUGAS: SETUJU & TOLAK -->
                                        <?php if ($is_menunggu && $status != 'disetujui' && $status != 'ditolak' && strpos($status, 'kembali') === false): ?>
                                            <a href="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $row['id_peminjaman']; ?>&aksi=setuju" class="btn btn-success btn-sm rounded-pill px-3 fw-semibold me-1">
                                                <i class="fa-solid fa-check me-1"></i> Setuju
                                            </a>
                                            <a href="<?= $_SERVER['PHP_SELF']; ?>?id=<?= $row['id_peminjaman']; ?>&aksi=tolak" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold">
                                                <i class="fa-solid fa-xmark me-1"></i> Tolak
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small"><i class="fa-solid fa-lock me-1"></i> Selesai</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>