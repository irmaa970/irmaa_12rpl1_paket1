<?php
session_start();
include '../connection.php';

// Ambil role dari session (cek beberapa nama session yang umum)
$user_role = strtolower($_SESSION['role'] ?? $_SESSION['level'] ?? '');

// Pengecekan login & role
if (!isset($_SESSION['username'])) {
    die("<div style='padding:20px; font-family:sans-serif;'>
            <h3>Akses Ditolak</h3>
            <p>Anda belum login. Silakan <a href='../login.php'>Login</a> terlebih dahulu.</p>
         </div>");
}

if ($user_role !== 'admin' && $user_role !== 'petugas') {
    die("<div style='padding:20px; font-family:sans-serif;'>
            <h3>Akses Ditolak</h3>
            <p>Halaman ini hanya untuk Admin atau Petugas. Role Anda saat ini: <b>" . htmlspecialchars($_SESSION['role'] ?? 'Kosong') . "</b></p>
            <p><a href='../home.php'>Kembali ke Home</a></p>
         </div>");
}

// Ambil ID peminjaman dari URL (cek ?id= atau ?id_peminjaman=)
$id_peminjaman = $_GET['id'] ?? $_GET['id_peminjaman'] ?? 0;

if ($id_peminjaman <= 0) {
    die("<div style='padding:20px; font-family:sans-serif;'>
            <h3>ID Tidak Valid</h3>
            <p>ID Peminjaman tidak ditemukan di URL.</p>
            <p><a href='tampil.php'>Kembali ke Data Peminjaman</a></p>
         </div>");
}

// Ambil data peminjaman berdasarkan ID
$query  = "SELECT * FROM peminjaman WHERE id_peminjaman = '$id_peminjaman'";
$result = mysqli_query($conn, $query);
$data   = mysqli_fetch_assoc($result);

if (!$data) {
    die("<div style='padding:20px; font-family:sans-serif;'>
            <h3>Data Tidak Ditemukan</h3>
            <p>Data peminjaman dengan ID $id_peminjaman tidak ada di database.</p>
            <p><a href='tampil.php'>Kembali ke Data Peminjaman</a></p>
         </div>");
}

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    
    // Update data peminjaman
    $update_query = "UPDATE peminjaman SET status = '$status' WHERE id_peminjaman = '$id_peminjaman'";
    $update_res   = mysqli_query($conn, $update_query);

    if ($update_res) {
        header("Location: tampil.php?pesan=update_berhasil");
        exit();
    } else {
        $error = "Gagal memperbarui data peminjaman.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fredoka', sans-serif; background-color: #f0f9ff; color: #334155; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #ffffff; }
    </style>
</head>
<body>
    <div class="container my-5" style="max-width: 600px;">
        <div class="card card-custom p-4">
            <h4 class="fw-bold text-primary mb-3">Edit Status Peminjaman</h4>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Peminjaman</label>
                    <select name="status" class="form-select" required>
                        <option value="Menunggu ACC" <?= ($data['status'] == 'Menunggu ACC') ? 'selected' : ''; ?>>Menunggu ACC</option>
                        <option value="Disetujui" <?= ($data['status'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui / Dipinjam</option>
                        <option value="Ditolak" <?= ($data['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                        <option value="Selesai" <?= ($data['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai / Dikembalikan</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="tampil.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>