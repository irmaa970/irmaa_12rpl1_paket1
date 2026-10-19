<?php
session_start();
include '../connection.php';

// Cek autentikasi & hak akses admin
if (!isset($_SESSION['username']) || strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../home.php");
    exit();
}

// Ambil data seluruh user
$query  = "SELECT * FROM users ORDER BY id_user DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data User</title>
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
                    <i class="fa-solid fa-users me-2"></i>Manajemen Data User
                </h3>
                <p class="text-muted small mb-0">Kelola akun pengguna, peran (role), dan status peminjaman.</p>
            </div>
            <div>
                <a href="tambah.php" class="btn btn-primary rounded-pill px-4 fw-semibold me-2">
                    <i class="fa-solid fa-user-plus me-1"></i> Tambah User
                </a>
                <a href="../home.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">
                    <i class="fa-solid fa-house me-1"></i> Home
                </a>
            </div>
        </div>

        <!-- Notifikasi Status Hapus (Pure PHP / Bootstrap Alert) -->
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal_masih_pinjam'): ?>
            <div class="alert alert-danger rounded-3 fw-semibold mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> Gagal menghapus! User ini masih meminjam barang dan belum mengembalikannya.
            </div>
        <?php elseif (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus_berhasil'): ?>
            <div class="alert alert-success rounded-3 fw-semibold mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> Data user berhasil dihapus!
            </div>
        <?php endif; ?>

        <div class="card card-custom p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-custom">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama Lengkap</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($result && mysqli_num_rows($result) > 0):
                            while ($user = mysqli_fetch_assoc($result)): 
                                $role = strtolower($user['role'] ?? 'peminjam');
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($user['username'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($user['nama_lengkap'] ?? $user['nama'] ?? '-'); ?></td>
                                <td>
                                    <?php if ($role == 'admin'): ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Admin</span>
                                    <?php elseif ($role == 'petugas'): ?>
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">Petugas</span>
                                    <?php else: ?>
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">Peminjam</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="edit.php?id=<?= $user['id_user']; ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-semibold text-white me-1">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                    </a>
                                    <a href="hapus.php?id=<?= $user['id_user']; ?>" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold">
                                        <i class="fa-solid fa-trash me-1"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data user.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>