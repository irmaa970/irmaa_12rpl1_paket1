<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['id_user'] ?? 0;

// Query peminjaman aktif milik user
$query_pinjam = "SELECT * FROM peminjaman 
                 WHERE id_user = '$id_user' 
                 AND status = 'Disetujui'
                 ORDER BY id_peminjaman DESC";

$result_pinjam = mysqli_query($conn, $query_pinjam);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_peminjaman = intval($_POST['id_peminjaman']);
    $tgl_input     = mysqli_real_escape_string($conn, $_POST['tgl_pengembalian']);

    // 1. Simpan ke tabel pengembalian
    $col_query = mysqli_query($conn, "SHOW COLUMNS FROM pengembalian");
    $existing_columns = [];
    if ($col_query) {
        while ($col = mysqli_fetch_assoc($col_query)) {
            $existing_columns[] = $col['Field'];
        }
    }

    $data_to_insert = ['id_peminjaman' => $id_peminjaman];

    foreach (['tanggal_pengembalian', 'tgl_pengembalian', 'tanggal_kembali', 'tgl_kembali', 'tanggal'] as $col_name) {
        if (in_array($col_name, $existing_columns)) {
            $data_to_insert[$col_name] = $tgl_input;
            break;
        }
    }

    foreach (['kondisi_kembali', 'kondisi', 'kondisi_barang'] as $col_name) {
        if (in_array($col_name, $existing_columns)) {
            $data_to_insert[$col_name] = 'Baik';
            break;
        }
    }

    if (in_array('denda', $existing_columns)) $data_to_insert['denda'] = 0;
    if (in_array('status_denda', $existing_columns)) $data_to_insert['status_denda'] = 'Tidak Ada Denda';

    foreach (['status_pengembalian', 'status', 'status_kembali'] as $col_name) {
        if (in_array($col_name, $existing_columns)) {
            $data_to_insert[$col_name] = 'Menunggu Konfirmasi';
            break;
        }
    }

    $fields = implode(", ", array_keys($data_to_insert));
    $values = implode("', '", array_values($data_to_insert));

    if (mysqli_query($conn, "INSERT INTO pengembalian ($fields) VALUES ('$values')")) {
        
        // 2. Kembalikan stok alat di tabel alat
        $query_detail = "SELECT id_alat, jumlah FROM detail_peminjaman WHERE id_peminjaman = '$id_peminjaman'";
        $res_detail   = mysqli_query($conn, $query_detail);

        while ($row_detail = mysqli_fetch_assoc($res_detail)) {
            $id_alat_kembali = $row_detail['id_alat'];
            $jumlah_kembali  = (int)$row_detail['jumlah'];

            if ($id_alat_kembali > 0) {
                mysqli_query($conn, "UPDATE alat SET stok = stok + $jumlah_kembali WHERE id_alat = '$id_alat_kembali'");
            }
        }

        // 3. Ubah status peminjaman jadi Dikembalikan
        mysqli_query($conn, "UPDATE peminjaman SET status = 'Dikembalikan' WHERE id_peminjaman = '$id_peminjaman'");

        header("Location: tampil.php?pesan=pengajuan_sukses");
        exit();
    } else {
        $error = "Gagal menyimpan pengajuan: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Pengembalian Alat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fredoka', sans-serif; background-color: #f0f9ff; color: #334155; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #ffffff; }
    </style>
</head>
<body>

    <div class="container my-5" style="max-width: 600px;">
        <div class="card card-custom p-4">
            <h4 class="fw-bold text-primary mb-1">
                <i class="fa-solid fa-paper-plane me-2"></i>Form Pengajuan Pengembalian
            </h4>
            <p class="text-muted small mb-4">Pilih alat yang sedang dipinjam, lalu serahkan fisik alat langsung ke petugas.</p>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger rounded-3 fw-semibold mb-3"><?= $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Peminjaman Alat</label>
                    <select name="id_peminjaman" class="form-select" required>
                        <option value="">-- Pilih Alat yang Dipinjam --</option>
                        <?php if ($result_pinjam && mysqli_num_rows($result_pinjam) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result_pinjam)): ?>
                                <option value="<?= $row['id_peminjaman']; ?>">
                                    <?= htmlspecialchars($row['nama_alat']); ?> (Tanggal Pinjam: <?= htmlspecialchars($row['tanggal_pinjam']); ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada alat yang sedang dipinjam (Disetujui)</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Tanggal Dikembalikan</label>
                    <input type="date" name="tgl_pengembalian" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="alert alert-info rounded-3 small mb-4">
                    <i class="fa-solid fa-circle-info me-1"></i> Setelah mengirim pengajuan, harap segera serahkan barang fisik ke petugas untuk pengecekan kondisi dan konfirmasi denda.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="tampil.php" class="btn btn-secondary rounded-pill px-4 fw-semibold">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>