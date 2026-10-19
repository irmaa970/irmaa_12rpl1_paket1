<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Header Mencegah Cache Browser (Mencegah user masuk lagi via tombol Back setelah logout)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$username = $_SESSION['username'] ?? 'User';
$role     = strtolower($_SESSION['role'] ?? 'peminjam'); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Sistem Peminjaman Alat</title>
    
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar-custom {
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .hero-banner {
            background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.2);
        }

        .card-menu {
            border: none;
            border-radius: 18px;
            background: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card-menu:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(2, 132, 199, 0.15);
            color: #0284c7;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 15px;
        }

        .about-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e0f2fe;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.03);
        }

        footer {
            margin-top: auto;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <!-- NAVBAR ATAS -->
    <nav class="navbar navbar-expand navbar-custom sticky-top py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center gap-2" href="home.php">
                <i class="fa-solid fa-boxes-packing text-info fs-3"></i> PinjamAlat
            </a>

            <div class="d-flex align-items-center gap-3">
                <a class="nav-link fw-semibold text-primary d-none d-sm-inline" href="home.php">
                    <i class="fa-solid fa-house me-1"></i> Home
                </a>
                <a class="nav-link fw-semibold text-secondary d-none d-sm-inline" href="#tentang-kami">
                    <i class="fa-solid fa-circle-info me-1"></i> Tentang
                </a>
                <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3 py-2 fw-semibold">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA -->
    <div class="container my-4">

        <!-- HERO BANNER -->
        <div class="hero-banner mb-5 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-2 fw-bold text-uppercase">
                    <i class="fa-solid fa-user-shield me-1"></i> Role: <?= htmlspecialchars($_SESSION['role'] ?? 'Peminjam'); ?>
                </span>
                <h2 class="fw-bold mb-1">Selamat datang, <?= htmlspecialchars($username); ?>! 👋</h2>
                <p class="mb-0 opacity-90">
                    <?php 
                        if ($role == 'admin') {
                            echo "Kelola akun pengguna, kategori barang, dan master data alat.";
                        } elseif ($role == 'petugas') {
                            echo "Kelola persetujuan peminjaman, pengembalian, cetak laporan, dan log aktivitas.";
                        } else {
                            echo "Cari alat yang kamu butuhkan dan ajukan peminjaman dengan mudah.";
                        }
                    ?>
                </p>
            </div>
            <div class="d-none d-md-block opacity-75 fs-1 pe-3">
                <i class="fa-solid fa-toolbox fa-2x"></i>
            </div>
        </div>

        <!-- MENU FITUR (SESUAI ROLE) -->
        <div class="mb-5">
            <h4 class="fw-bold text-primary mb-4 d-flex align-items-center gap-2">
                <i class="fa-solid fa-border-all"></i> Menu Utama
            </h4>

            <div class="row g-4">

                <!-- 1. MENU PEMINJAM / SISWA -->
                <?php if ($role == 'peminjam' || $role == 'siswa') : ?>
                    <div class="col-6 col-md-3">
                        <a href="alat/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-info-subtle text-info mx-auto">
                                <i class="fa-solid fa-wrench"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Daftar Alat</h6>
                            <small class="text-muted">Cek stok alat</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-3">
                        <a href="peminjaman/tambah.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-success-subtle text-success mx-auto">
                                <i class="fa-solid fa-plus-circle"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Ajukan Pinjaman</h6>
                            <small class="text-muted">Form peminjaman</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-3">
                        <a href="peminjaman/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-primary-subtle text-primary mx-auto">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Peminjaman Saya</h6>
                            <small class="text-muted">Status persetujuan</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-3">
                        <a href="pengembalian/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-warning-subtle text-warning mx-auto">
                                <i class="fa-solid fa-rotate-left"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Denda & Pengembalian</h6>
                            <small class="text-muted">Rincian denda kamu</small>
                        </a>
                    </div>
                <?php endif; ?>


                <!-- 2. MENU PETUGAS -->
                <?php if ($role == 'petugas') : ?>
                    <div class="col-6 col-md-6">
                        <a href="alat/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-info-subtle text-info mx-auto">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Daftar Alat</h6>
                            <small class="text-muted">Kelola barang & stok</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-6">
                        <a href="peminjaman/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-success-subtle text-success mx-auto">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Persetujuan Pinjam</h6>
                            <small class="text-muted">ACC / Tolak pinjaman</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-6">
                        <a href="pengembalian/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-warning-subtle text-warning mx-auto">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Proses Pengembalian</h6>
                            <small class="text-muted">Catat kondisi & denda</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-6">
                        <a href="laporan.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-danger-subtle text-danger mx-auto">
                                <i class="fa-solid fa-print"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Laporan & Cetak</h6>
                            <small class="text-muted">Cetak laporan transaksi</small>
                        </a>
                    </div>
                <?php endif; ?>


                <!-- 3. MENU ADMIN -->
                <?php if ($role == 'admin') : ?>
                    <div class="col-6 col-md-4">
                        <a href="users/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-danger-subtle text-danger mx-auto">
                                <i class="fa-solid fa-users-gear"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Kelola Users</h6>
                            <small class="text-muted">Data akun & hak akses</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-4">
                        <a href="kategori/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-purple-subtle text-purple mx-auto" style="background:#f3e8ff; color:#9333ea;">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <h6 class="fw-bold mb-1"> Kelola Kategori</h6>
                            <small class="text-muted">Kelola jenis barang</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-4">
                        <a href="alat/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-info-subtle text-info mx-auto">
                                <i class="fa-solid fa-boxes-stacked"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Kelola Alat</h6>
                            <small class="text-muted">Tambah & edit data alat</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-4">
                        <a href="peminjaman/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-success-subtle text-success mx-auto">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Kelola data Peminjaman</h6>
                            <small class="text-muted">ACC / Tolak pinjaman</small>
                        </a>
                    </div>
                    
                    <div class="col-6 col-md-4">
                        <a href="pengembalian/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-warning-subtle text-warning mx-auto">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Kelola data Pengembalian</h6>
                            <small class="text-muted">Catat kondisi & denda</small>
                        </a>
                    </div>

                    <div class="col-6 col-md-4">
                        <a href="log_aktivitas/tampil.php" class="card card-menu h-100 p-4 text-center">
                            <div class="icon-box bg-secondary-subtle text-secondary mx-auto">
                                <i class="fa-solid fa-file-lines"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Log Aktivitas</h6>
                            <small class="text-muted">Riwayat aktivitas sistem</small>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>

        <!-- TENTANG KAMI -->
        <div id="tentang-kami" class="about-card p-4 p-md-5 my-5">
            <div class="row align-items-center g-4">
                <div class="col-md-3 text-center">
                    <div class="p-3 bg-light rounded-circle d-inline-block text-primary">
                        <i class="fa-solid fa-school fa-4x"></i>
                    </div>
                </div>
                <div class="col-md-9">
                    <h4 class="fw-bold text-primary mb-2">Tentang Aplikasi</h4>
                    <p class="text-muted mb-3">
                        Sistem Peminjaman Alat diciptakan untuk mempermudah siswa dan petugas sekolah dalam mendata peminjaman, pengembalian, serta pengecekan ketersediaan stok barang secara efisien dan transparan.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-check-circle me-1"></i> Cepat & Praktis
                        </span>
                        <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill">
                            <i class="fa-solid fa-shield-halved me-1"></i> Terdata Otomatis
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="py-3 text-center text-muted small">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y'); ?> <strong>PinjamAlat</strong> — Paket 1 XII RPL 1. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>