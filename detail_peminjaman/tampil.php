<?php

session_start();

include "../connection.php";

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// Mengambil semua data detail peminjaman
$sql = "SELECT * FROM detail_peminjaman";

$result = $con->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Detail Peminjaman</title>

    <!-- Menggunakan CSS -->
    <link rel="stylesheet" href="../data.css">

</head>

<body>

<h2>📦 Detail Peminjaman</h2>


<!-- Tombol Tambah Data -->
<div class="tombol">

    <a href="tambah.php">
        <button>+ Tambah Data</button>
    </a>

</div>


<table>

    <tr>

        <th>ID Detail</th>
        <th>ID Peminjaman</th>
        <th>Nama Peminjam</th>
        <th>Nama Alat</th>
        <th>ID Alat</th>
        <th>Jumlah</th>
        <th>Aksi</th>

    </tr>


    <?php if ($result->num_rows > 0) { ?>

        <?php foreach ($result as $detail) { ?>

            <tr>

                <td>
                    <?= $detail['id_detail']; ?>
                </td>

                <td>
                    <?= $detail['id_peminjaman']; ?>
                </td>

                <td>
                    <?= $detail['nama_peminjam']; ?>
                </td>

                <td>
                    <?= $detail['nama_alat']; ?>
                </td>

                <td>
                    <?= $detail['id_alat']; ?>
                </td>

                <td>
                    <?= $detail['jumlah']; ?>
                </td>

                <td>

                    <a href="edit.php?id=<?= $detail['id_detail']; ?>">
                        <button>Edit</button>
                    </a>

                    <a href="hapus.php?id=<?= $detail['id_detail']; ?>"
                       onclick="return confirm('Yakin ingin menghapus data ini?')">

                        <button class="hapus">
                            Hapus
                        </button>

                    </a>

                </td>

            </tr>

        <?php } ?>

    <?php } else { ?>

        <tr>

            <td colspan="7">
                Belum ada detail peminjaman.
            </td>

        </tr>

    <?php } ?>

</table>


<br>


<!-- Tombol Kembali ke Home -->
<div class="tombol">

    <a href="../home.php">
        <button>← Kembali ke Home</button>
    </a>

</div>

</body>

</html>