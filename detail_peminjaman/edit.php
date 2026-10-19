<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

include "../connection.php";

$id = $_GET['id'];

$data = $con->query(
    "SELECT * FROM detail_peminjaman
     WHERE id_detail='$id'"
);

$detail = $data->fetch_assoc();

if (isset($_POST['simpan'])) {

    $id_peminjaman = $_POST['id_peminjaman'];
    $id_alat = $_POST['id_alat'];
    $jumlah = $_POST['jumlah'];

    $sql = "UPDATE detail_peminjaman SET
            id_peminjaman='$id_peminjaman',
            id_alat='$id_alat',
            jumlah='$jumlah'
            WHERE id_detail='$id'";

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
    <title>Edit Detail</title>
    <link rel="stylesheet" href="../data.css">
</head>

<body>

<h2>Edit Detail Peminjaman</h2>

<form method="POST">

<table class="form-tambah">

<tr>
    <td>Peminjaman</td>
    <td>
        <select name="id_peminjaman">

            <?php foreach ($peminjaman as $p) { ?>

                <option value="<?= $p['id_peminjaman']; ?>"
                    <?= $p['id_peminjaman'] == $detail['id_peminjaman'] ? 'selected' : ''; ?>>

                    Peminjaman #<?= $p['id_peminjaman']; ?>

                </option>

            <?php } ?>

        </select>
    </td>
</tr>

<tr>
    <td>Alat</td>
    <td>
        <select name="id_alat">

            <?php foreach ($alat as $a) { ?>

                <option value="<?= $a['id_alat']; ?>"
                    <?= $a['id_alat'] == $detail['id_alat'] ? 'selected' : ''; ?>>

                    <?= $a['nama_alat']; ?>

                </option>

            <?php } ?>

        </select>
    </td>
</tr>

<tr>
    <td>Jumlah</td>
    <td>
        <input type="number"
               name="jumlah"
               min="1"
               value="<?= $detail['jumlah']; ?>">
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