<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

include "../connection.php";

if (isset($_POST['simpan'])) {

    $id_peminjaman = $_POST['id_peminjaman'];
    $id_alat = $_POST['id_alat'];
    $jumlah = $_POST['jumlah'];

    $sql = "INSERT INTO detail_peminjaman
            (id_peminjaman, id_alat, jumlah)
            VALUES
            ('$id_peminjaman', '$id_alat', '$jumlah')";

    $con->query($sql);

    header("Location: tampil.php");
    exit;
}

$peminjaman = $con->query("SELECT * FROM peminjaman");
$alat = $con->query("SELECT * FROM alat");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Detail</title>
    <link rel="stylesheet" href="../data.css">
</head>

<body>

<h2>Tambah Detail Peminjaman</h2>

<form method="POST">

<table class="form-tambah">

<tr>
    <td>Peminjaman</td>
    <td>
        <select name="id_peminjaman" required>

            <option value="">-- Pilih Peminjaman --</option>

            <?php foreach ($peminjaman as $p) { ?>

                <option value="<?= $p['id_peminjaman']; ?>">
                    Peminjaman #<?= $p['id_peminjaman']; ?>
                </option>

            <?php } ?>

        </select>
    </td>
</tr>

<tr>
    <td>Alat</td>
    <td>
        <select name="id_alat" required>

            <option value="">-- Pilih Alat --</option>

            <?php foreach ($alat as $a) { ?>

                <option value="<?= $a['id_alat']; ?>">
                    <?= $a['nama_alat']; ?>
                </option>

            <?php } ?>

        </select>
    </td>
</tr>

<tr>
    <td>Jumlah</td>
    <td>
        <input type="number" name="jumlah" min="1" required>
    </td>
</tr>

<tr>
    <td colspan="2">
        <input type="submit" name="simpan" value="Simpan">
    </td>
</tr>

</table>

</form>

<a href="tampil.php">Kembali</a>

</body>
</html>