<?php
session_start();
include '../connection.php';

// Menggunakan variabel $conn yang sesuai dengan connection.php
$query  = "SELECT * FROM kategori ORDER BY id_kategori ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori</title>
    <link rel="stylesheet" href="../data.css">
</head>
<body>

    <h2>Daftar Kategori Alat</h2>

    <a href="tambah.php">
        <button type="button">+ Tambah Kategori</button>
    </a>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
                    <td>
                        <a href="edit.php?id=<?= $row['id_kategori']; ?>">
                            <button type="button">Edit</button>
                        </a>
                        <a href="hapus.php?id=<?= $row['id_kategori']; ?>" onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                            <button type="button">Hapus</button>
                        </a>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada data kategori.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="../home.php" class="btn-floating">
        <button type="button">Home</button>
    </a>

</body>
</html>