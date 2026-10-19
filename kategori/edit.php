<?php
session_start();
include '../connection.php';

$id = $_GET['id'] ?? 0;

// Ambil data kategori berdasarkan ID
$query_data  = "SELECT * FROM kategori WHERE id_kategori = '$id'";
$result_data = mysqli_query($conn, $query_data);
$data        = mysqli_fetch_assoc($result_data);

if (!$data) {
    header("Location: tampil.php");
    exit();
}

// Proses update data kategori
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $update = "UPDATE kategori SET nama_kategori = '$nama_kategori' WHERE id_kategori = '$id'";

        if (mysqli_query($conn, $update)) {
            header("Location: tampil.php");
            exit();
        } else {
            $error = "Gagal memperbarui kategori: " . mysqli_error($conn);
        }
    } else {
        $error = "Nama kategori tidak boleh kosong!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Edit Kategori Alat</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="<?= htmlspecialchars($data['nama_kategori']); ?>" required autocomplete="off">
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>