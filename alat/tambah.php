<?php
session_start();
include '../connection.php';

// Ambil data kategori untuk pilihan dropdown
$query_kategori  = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $query_kategori);

// Proses simpan data alat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kategori = $_POST['id_kategori'];
    $nama_alat   = mysqli_real_escape_string($conn, $_POST['nama_alat']);
    $stok        = (int)$_POST['stok'];
    $kondisi     = mysqli_real_escape_string($conn, $_POST['kondisi']);

    if (!empty($nama_alat) && !empty($id_kategori)) {
        $query = "INSERT INTO alat (id_kategori, nama_alat, stok, kondisi) 
                  VALUES ('$id_kategori', '$nama_alat', '$stok', '$kondisi')";

        if (mysqli_query($conn, $query)) {
            header("Location: tampil.php");
            exit();
        } else {
            $error = "Gagal menambah alat: " . mysqli_error($conn);
        }
    } else {
        $error = "Semua kolom wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alat</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Tambah Data Alat</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="id_kategori">Kategori Alat</label>
                <select name="id_kategori" id="id_kategori" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kat = mysqli_fetch_assoc($result_kategori)): ?>
                        <option value="<?= $kat['id_kategori']; ?>">
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_alat">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" placeholder="Masukkan nama alat" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="stok">Jumlah Stok</label>
                <input type="number" id="stok" name="stok" min="0" value="1" required>
            </div>

            <div class="form-group">
                <label for="kondisi">Kondisi Alat</label>
                <select name="kondisi" id="kondisi" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Alat</button>
        </form>
    </div>

</body>
</html>