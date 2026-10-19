<?php
session_start();

// Mengambil file koneksi yang berada di luar folder 'alat'
include '../connection.php';

$db = isset($koneksi) ? $koneksi : (isset($conn) ? $conn : null);

// Cek status login user
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$role = strtolower($_SESSION['role'] ?? 'peminjam');

// --- CLASS OOP UNTUK KATALOG ALAT ---
class AlatSiswaHandler {
    private $db;

    public function __construct($koneksi) {
        $this->db = $koneksi;
    }

    // Ambil daftar kategori dari tabel kategori
    public function getDaftarKategori() {
        return $this->db->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
    }

    // Ambil daftar alat + JOIN dengan tabel kategori
    public function getDaftarAlat($id_kategori_filter = '') {
        if (!empty($id_kategori_filter)) {
            $stmt = $this->db->prepare("SELECT alat.*, kategori.nama_kategori 
                                       FROM alat 
                                       LEFT JOIN kategori ON alat.id_kategori = kategori.id_kategori 
                                       WHERE alat.id_kategori = ? 
                                       ORDER BY alat.nama_alat ASC");
            $stmt->bind_param("i", $id_kategori_filter);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            return $this->db->query("SELECT alat.*, kategori.nama_kategori 
                                     FROM alat 
                                     LEFT JOIN kategori ON alat.id_kategori = kategori.id_kategori 
                                     ORDER BY alat.nama_alat ASC");
        }
    }

    // Method Hapus Alat (Khusus Admin)
    public function hapusAlat($id_alat) {
        $stmt = $this->db->prepare("DELETE FROM alat WHERE id_alat = ?");
        $stmt->bind_param("i", $id_alat);
        return $stmt->execute();
    }
}

// Inisialisasi Object OOP
$app = new AlatSiswaHandler($db);

// PROSES HAPUS (KHUSUS ADMIN)
if ($role == 'admin' && isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id_hapus = (int)$_GET['id'];
    if ($app->hapusAlat($id_hapus)) {
        header("Location: tampil.php?pesan=berhasil_hapus");
        exit();
    }
}

// Ambil ID Kategori dari filter dropdown
$id_kategori_dipilih = isset($_GET['id_kategori']) ? $_GET['id_kategori'] : '';

$list_kategori = $app->getDaftarKategori();
$daftar_alat   = $app->getDaftarAlat($id_kategori_dipilih);

// Path pasti untuk keluar dari folder alat menuju home.php
$home_url = '../home.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alat</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Fredoka', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f0f9ff; padding: 30px 20px; }
        .container { max-width: 950px; margin: 0 auto; background: #ffffff; padding: 25px; border-radius: 16px; box-shadow: 0 10px 25px rgba(2, 132, 199, 0.08); border: 1px solid #e0f2fe; }
        
        .header-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        h2 { color: #0369a1; font-size: 22px; }

        .filter-form { display: flex; align-items: center; gap: 8px; }
        .filter-form label { font-size: 13px; color: #0369a1; font-weight: 600; }
        .select-kategori { padding: 8px 12px; border: 1px solid #bae6fd; border-radius: 8px; font-size: 13px; outline: none; background: #f0f9ff; color: #0369a1; cursor: pointer; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e0f2fe; color: #0369a1; text-align: left; padding: 12px 10px; font-size: 13px; }
        td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; color: #334155; font-size: 13px; vertical-align: middle; }
        tr:hover { background-color: #f8fafc; }
        
        .badge-kategori { background-color: #e0f2fe; color: #0369a1; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block; }
        .badge-stok { background-color: #dcfce7; color: #15803d; padding: 4px 8px; border-radius: 12px; font-weight: 600; font-size: 11px; }
        .badge-habis { background-color: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 12px; font-weight: 600; font-size: 11px; }

        /* Styling Tombol Aksi */
        .btn-tambah { background-color: #0284c7; color: #fff; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; }
        .btn-edit { background-color: #eab308; color: #fff; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; margin-right: 4px; }
        .btn-hapus { background-color: #ef4444; color: #fff; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; }
        .alert-success { background-color: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 15px; font-size: 13px; font-weight: 600; }

        /* Tombol Home */
        .btn-home { 
            background-color: #64748b; 
            color: #ffffff; 
            padding: 8px 18px; 
            border-radius: 20px; 
            text-decoration: none; 
            font-size: 13px; 
            font-weight: 600; 
            display: inline-flex; 
            align-items: center; 
            gap: 6px; 
            transition: background 0.2s;
        }
        .btn-home:hover { 
            background-color: #475569; 
        }
    </style>
</head>
<body>

<div class="container">
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'berhasil_hapus'): ?>
        <div class="alert-success">
            Data alat berhasil dihapus!
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2>Daftar Alat Tersedia</h2>

        <div style="display: flex; gap: 10px; align-items: center;">
            <!-- TOMBOL TAMBAH DATA (KHUSUS ADMIN) -->
            <?php if ($role == 'admin'): ?>
                <a href="tambah.php" class="btn-tambah">+ Tambah Alat</a>
            <?php endif; ?>

            <!-- Dropdown Filter Kategori -->
            <form method="GET" action="" class="filter-form">
                <label for="id_kategori">Filter Kategori:</label>
                <select name="id_kategori" id="id_kategori" class="select-kategori" onchange="this.form.submit()">
                    <option value="">-- Semua Kategori --</option>
                    <?php 
                    if ($list_kategori && $list_kategori->num_rows > 0) {
                        while ($kat = $list_kategori->fetch_assoc()) {
                            $selected = ($id_kategori_dipilih == $kat['id_kategori']) ? 'selected' : '';
                            echo "<option value='" . $kat['id_kategori'] . "' $selected>" . htmlspecialchars($kat['nama_kategori']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </form>

            <!-- TOMBOL HOME (Mengarahkan ke ../home.php) -->
            <a href="<?= $home_url; ?>" class="btn-home">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                Home
            </a>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th>Jumlah Stok</th>
                <th>Status</th>
                <?php if ($role == 'admin'): ?>
                    <th style="text-align: center;">Aksi</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($daftar_alat && $daftar_alat->num_rows > 0) {
                while ($row = $daftar_alat->fetch_assoc()) {
                    $stok = (int)$row['stok'];
                    $nama_kategori = !empty($row['nama_kategori']) ? $row['nama_kategori'] : '-';
            ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><strong><?= htmlspecialchars($row['nama_alat']); ?></strong></td>
                        <td><span class="badge-kategori"><?= htmlspecialchars($nama_kategori); ?></span></td>
                        <td><?= $stok; ?> unit</td>
                        <td>
                            <?php if ($stok > 0) : ?>
                                <span class="badge-stok">Tersedia</span>
                            <?php else : ?>
                                <span class="badge-habis">Tidak Tersedia</span>
                            <?php endif; ?>
                        </td>

                        <?php if ($role == 'admin'): ?>
                            <td style="text-align: center;">
                                <a href="edit.php?id=<?= $row['id_alat']; ?>" class="btn-edit">Edit</a>
                                <a href="tampil.php?id=<?= $row['id_alat']; ?>&aksi=hapus" onclick="return confirm('Yakin ingin menghapus alat ini?')" class="btn-hapus">Hapus</a>
                            </td>
                        <?php endif; ?>
                    </tr>
            <?php 
                }
            } else {
                $colspan = ($role == 'admin') ? '6' : '5';
                echo "<tr><td colspan='$colspan' style='text-align:center; padding:20px; color:#94a3b8;'>Belum ada data alat pada kategori ini.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>