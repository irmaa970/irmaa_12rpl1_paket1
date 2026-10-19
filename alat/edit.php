<?php
session_start();
include '../connection.php';

$id = $_GET['id'] ?? 0;

// Ambil data alat berdasarkan ID
$query_data  = "SELECT * FROM alat WHERE id_alat = '$id'";
$result_data = mysqli_query($conn, $query_data);
$data        = mysqli_fetch_assoc($result_data);

if (!$data) {
    header("Location: tampil.php");
    exit();
}

// Ambil daftar kategori untuk dropdown
$query_kategori  = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $query_kategori);

// Proses update data alat
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kategori = $_POST['id_kategori'];
    $nama_alat   = mysqli_real_escape_string($conn, $_POST['nama_alat']);
    $stok        = (int)$_POST['stok'];
    $kondisi     = mysqli_real_escape_string($conn, $_POST['kondisi']);

    $update = "UPDATE alat SET 
                id_kategori = '$id_kategori',
                nama_alat = '$nama_alat',
                stok = '$stok',
                kondisi = '$kondisi'
               WHERE id_alat = '$id'";

    if (mysqli_query($conn, $update)) {
        header("Location: tampil.php");
        exit();
    } else {
        $error = "Gagal memperbarui alat: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alat</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Edit Data Alat</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="id_kategori">Kategori Alat</label>
                <select name="id_kategori" id="id_kategori" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kat = mysqli_fetch_assoc($result_kategori)): ?>
                        <option value="<?= $kat['id_kategori']; ?>" <?= $kat['id_kategori'] == $data['id_kategori'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($kat['nama_kategori']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_alat">Nama Alat</label>
                <input type="text" id="nama_alat" name="nama_alat" value="<?= htmlspecialchars($data['nama_alat']); ?>" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="stok">Jumlah Stok</label>
                <input type="number" id="stok" name="stok" min="0" value="<?= $data['stok']; ?>" required>
            </div>

            <div class="form-group">
                <label for="kondisi">Kondisi Alat</label>
                <select name="kondisi" id="kondisi" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="Baik" <?= $data['kondisi'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                    <option value="Rusak Ringan" <?= $data['kondisi'] == 'Rusak Ringan' ? 'selected' : ''; ?>>Rusak Ringan</option>
                    <option value="Rusak Berat" <?= $data['kondisi'] == 'Rusak Berat' ? 'selected' : ''; ?>>Rusak Berat</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>