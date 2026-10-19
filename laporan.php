<?php
session_start();
// Gunakan ./connection.php karena file laporan.php sejajar dengan connection.php
include 'connection.php';

// Ambil filter tanggal jika ada
$tgl_mulai   = $_GET['tgl_mulai'] ?? '';
$tgl_selesai = $_GET['tgl_selesai'] ?? '';

// Build Query gabungan tabel peminjaman dan pengembalian
$query = "SELECT p.*, g.kondisi_kembali, g.denda, g.status_denda 
          FROM peminjaman p 
          LEFT JOIN pengembalian g ON p.id_peminjaman = g.id_peminjaman";

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query .= " WHERE p.tanggal_pinjam BETWEEN '$tgl_mulai' AND '$tgl_selesai'";
}

$query .= " ORDER BY p.id_peminjaman DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat</title>
    <!-- Jalur CSS disesuaikan ke data.css di folder utama -->
    <link rel="stylesheet" href="data.css">
    <style>
        .filter-box {
            background-color: #f0f9ff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .filter-box input[type="date"] {
            padding: 8px 12px;
            border: 2px solid #bae6fd;
            border-radius: 8px;
        }
        .btn-print {
            background-color: #0284c7;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Format Tampilan Khusus Cetak/Print */
        @media print {
            .filter-box, .btn-floating, button, a {
                display: none !important;
            }
            body {
                background: white;
                color: black;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #000 !important;
                padding: 8px !important;
                color: black !important;
            }
            th {
                background-color: #f3f4f6 !important;
            }
        }
    </style>
</head>
<body>

    <h2>Laporan Peminjaman & Pengembalian Alat</h2>

    <div class="filter-box">
        <form method="GET" action="" style="display: flex; gap: 10px; align-items: center;">
            <label>Dari:</label>
            <input type="date" name="tgl_mulai" value="<?= htmlspecialchars($tgl_mulai); ?>" required>
            
            <label>Sampai:</label>
            <input type="date" name="tgl_selesai" value="<?= htmlspecialchars($tgl_selesai); ?>" required>

            <button type="submit">Filter Data</button>
            <?php if (!empty($tgl_mulai)): ?>
                <a href="laporan.php"><button type="button" style="background-color: #64748b;">Reset</button></a>
            <?php endif; ?>
        </form>

        <button type="button" class="btn-print" onclick="window.print()">Cetak Laporan</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Rencana Kembali</th>
                <th>Status</th>
                <th>Kondisi Kembali</th>
                <th>Denda</th>
                <th>Status Denda</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($result && mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
                    $denda  = floatval($row['denda'] ?? 0);
                    $status = strtolower(trim($row['status'] ?? ''));
                    $st_denda_db = strtolower(trim($row['status_denda'] ?? ''));
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_peminjam'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['nama_alat'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pinjam'] ?? '-'); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_rencana_kembali'] ?? '-'); ?></td>
                    <td><b><?= htmlspecialchars($row['status'] ?? '-'); ?></b></td>
                    
                    <!-- Kondisi Kembali -->
                    <td>
                        <?php 
                        if ($status == 'dikembalikan') {
                            echo !empty($row['kondisi_kembali']) ? htmlspecialchars($row['kondisi_kembali']) : 'Baik';
                        } elseif ($status == 'disetujui') {
                            echo 'Belum Dikembalikan';
                        } elseif ($status == 'ditolak') {
                            echo 'Ditolak';
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>

                    <!-- Denda -->
                    <td>Rp <?= number_format($denda, 0, ',', '.'); ?></td>

                    <!-- Status Denda -->
                    <td>
                        <?php 
                        if ($status == 'ditolak') {
                            echo 'Tidak Ada';
                        } elseif ($denda > 0) {
                            echo ($st_denda_db == 'lunas') ? 'Lunas' : 'Belum Lunas';
                        } else {
                            echo 'Lunas';
                        }
                        ?>
                    </td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data laporan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Jalur tombol Home disesuaikan ke home.php di folder utama -->
    <a href="home.php" class="btn-floating">
        <button type="button">Home</button>
    </a>

</body>
</html>