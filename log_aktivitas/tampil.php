<?php
session_start();
include '../connection.php';

// Pastikan hanya user dengan role 'admin' yang dapat mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../home.php");
    exit();
}

// Query untuk mengambil log aktivitas beserta nama lengkap user
$query = "SELECT log_aktivitas.*, users.nama_lengkap, users.role 
          FROM log_aktivitas 
          JOIN users ON log_aktivitas.id_user = users.id_user 
          ORDER BY log_aktivitas.waktu DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas - Admin</title>
    <link rel="stylesheet" href="../data.css">
</head>
<body>
    <div class="container">
        <h2>Log Aktivitas Sistem</h2>
        <a href="../home.php" class="btn-back"> Home</a>
        <br><br>
        
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama User</th>
                    <th>Role</th>
                    <th>Aktivitas</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?= htmlspecialchars($row['role']); ?></td>
                        <td><?= htmlspecialchars($row['aktivitas']); ?></td>
                        <td><?= $row['waktu']; ?></td>
                    </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='5'>Belum ada log aktivitas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>