<?php
session_start();
include '../connection.php';

$db = isset($koneksi) ? $koneksi : (isset($conn) ? $conn : null);

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$username_login = $_SESSION['username'];

// Ambil data user dari database
$id_user_login = '';
$nama_lengkap_login = '';

$q_user = mysqli_query($db, "SELECT id_user, nama_lengkap FROM users WHERE username = '$username_login'");
if ($q_user && mysqli_num_rows($q_user) > 0) {
    $d_user = mysqli_fetch_assoc($q_user);
    $id_user_login      = $d_user['id_user'];
    $nama_lengkap_login = $d_user['nama_lengkap'];
}

// Ambil daftar alat yang stoknya > 0
$q_alat = mysqli_query($db, "SELECT * FROM alat WHERE stok > 0 ORDER BY nama_alat ASC");

// Proses Simpan Peminjaman
$pesan_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $id_alat                 = mysqli_real_escape_string($db, $_POST['id_alat']);
    $tanggal_pinjam          = mysqli_real_escape_string($db, $_POST['tanggal_pinjam']);
    $tanggal_rencana_kembali = mysqli_real_escape_string($db, $_POST['tanggal_rencana_kembali']);
    $jumlah                  = (int)$_POST['jumlah'];

    // Ambil nama alat berdasarkan id_alat
    $q_detail_alat = mysqli_query($db, "SELECT nama_alat, stok FROM alat WHERE id_alat = '$id_alat'");
    $d_alat = mysqli_fetch_assoc($q_detail_alat);
    $nama_alat = $d_alat['nama_alat'];

    if ($jumlah > $d_alat['stok']) {
        $pesan_error = "Jumlah peminjaman melebihi stok yang tersedia!";
    } else {
        // 1. Insert ke tabel peminjaman
        $query_peminjaman = "INSERT INTO peminjaman (id_user, nama_peminjam, nama_alat, tanggal_pinjam, tanggal_rencana_kembali, status) 
                            VALUES ('$id_user_login', '$nama_lengkap_login', '$nama_alat', '$tanggal_pinjam', '$tanggal_rencana_kembali', 'Menunggu')";
        
        if (mysqli_query($db, $query_peminjaman)) {
            $id_peminjaman_baru = mysqli_insert_id($db);

            // 2. Insert ke tabel detail_peminjaman
            $query_detail = "INSERT INTO detail_peminjaman (id_peminjaman, nama_peminjam, nama_alat, id_alat, jumlah) 
                             VALUES ('$id_peminjaman_baru', '$nama_lengkap_login', '$nama_alat', '$id_alat', '$jumlah')";
            mysqli_query($db, $query_detail);

            // 3. Catat ke log_aktivitas
            $aktivitas = "Mengajukan peminjaman " . $nama_alat;
            mysqli_query($db, "INSERT INTO log_aktivitas (id_user, aktivitas) VALUES ('$id_user_login', '$aktivitas')");

            echo "<script>alert('Pengajuan peminjaman berhasil dikirim!'); window.location.href='saya.php';</script>";
            exit();
        } else {
            $pesan_error = "Gagal mengajukan peminjaman: " . mysqli_error($db);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Ajukan Peminjaman</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Fredoka', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f0f9ff; padding: 30px 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.08); border: 1px solid #e0f2fe; }
        h2 { color: #0369a1; margin-bottom: 20px; font-size: 20px; text-align: center; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; color: #0369a1; font-weight: 600; font-size: 14px; }
        input, select { width: 100%; padding: 10px 12px; border: 1px solid #bae6fd; border-radius: 8px; outline: none; font-size: 14px; color: #334155; }
        input[readonly] { background-color: #f1f5f9; cursor: not-allowed; }
        .btn-submit { width: 100%; background-color: #0284c7; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 600; font-size: 15px; cursor: pointer; transition: background 0.2s; margin-top: 10px; }
        .btn-submit:hover { background-color: #0369a1; }
        .btn-back { display: block; text-align: center; margin-top: 12px; color: #64748b; text-decoration: none; font-size: 13px; }
        .alert { background-color: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Form Pengajuan Peminjaman</h2>

    <?php if (!empty($pesan_error)) : ?>
        <div class="alert"><?= $pesan_error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Nama Peminjam</label>
            <input type="text" value="<?= htmlspecialchars($nama_lengkap_login); ?>" readonly>
        </div>

        <div class="form-group">
            <label>Pilih Alat</label>
            <select name="id_alat" required>
                <option value="">-- Pilih Alat --</option>
                <?php while ($alat = mysqli_fetch_assoc($q_alat)) : ?>
                    <option value="<?= $alat['id_alat']; ?>">
                        <?= htmlspecialchars($alat['nama_alat']); ?> (Stok: <?= $alat['stok']; ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1" value="1" required>
        </div>

        <div class="form-group">
            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" value="<?= date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label>Tanggal Rencana Kembali</label>
            <input type="date" name="tanggal_rencana_kembali" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">Kirim Pengajuan</button>
        <a href="saya.php" class="btn-back">Batal & Kembali</a>
    </form>
</div>

</body>
</html>