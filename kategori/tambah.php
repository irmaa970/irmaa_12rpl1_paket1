<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = mysqli_real_escape_string($conn, $_POST['nama_kategori']);

    if (!empty($nama_kategori)) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')";

        if (mysqli_query($conn, $query)) {
            header("Location: tampil.php");
            exit();
        } else {
            $error = "Gagal menambah kategori: " . mysqli_error($conn);
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
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Tambah Kategori Alat</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_kategori">Nama Kategori</label>
                <input type="text" id="nama_kategori" name="nama_kategori" placeholder="Masukkan nama kategori" required autocomplete="off">
            </div>

            <button type="submit" class="btn-submit">Simpan Kategori</button>
        </form>
    </div>

</body>
</html>