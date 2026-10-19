<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$role    = strtolower($_SESSION['role'] ?? 'peminjam');
$id_user = $_SESSION['id_user'] ?? 0;

// Query Data Peminjaman Saya
if ($role == 'peminjam' || $role == 'siswa') {
    $query = "SELECT p.*, d.jumlah AS qty_detail 
              FROM peminjaman p 
              LEFT JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
              WHERE p.id_user = '$id_user' 
              ORDER BY p.id_peminjaman DESC";
} else {
    $query = "SELECT p.*, d.jumlah AS qty_detail 
              FROM peminjaman p 
              LEFT JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman 
              ORDER BY p.id_peminjaman DESC";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya</title>
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
                    <i class="fa-solid fa-boxes-packing me-2"></i>Peminjaman Saya
                </h3>
                <p class="text-muted small mb-0">Daftar riwayat peminjaman barang Anda.</p>
            </div>
            <div>
                <a href="tambah.php" class="btn btn-primary rounded-pill px-4 fw-semibold me-2">
                    <i class="fa-solid fa-plus me-1"></i> Pinjam Alat
                </a>
                <a href="../home.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Home
                </a>
            </div>
        </div>

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
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($row['nama_peminjam'] ?? $_SESSION['username'] ?? '-'); ?></td>
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
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Belum ada data peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>