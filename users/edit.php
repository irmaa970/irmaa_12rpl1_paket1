<?php
session_start();
include '../connection.php';

$id = $_GET['id'] ?? 0;

// Ambil data user berdasarkan ID
$query_data  = "SELECT * FROM users WHERE id_user = '$id'";
$result_data = mysqli_query($conn, $query_data);
$data        = mysqli_fetch_assoc($result_data);

if (!$data) {
    header("Location: tampil.php");
    exit();
}

// Proses update data user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role         = mysqli_real_escape_string($conn, $_POST['role']);
    $password     = $_POST['password'];

    // Jika password diisi, update password. Jika kosong, gunakan password lama.
    if (!empty($password)) {
        $password_fix = mysqli_real_escape_string($conn, $password);
        $update = "UPDATE users SET username = '$username', password = '$password_fix', nama_lengkap = '$nama_lengkap', role = '$role' WHERE id_user = '$id'";
    } else {
        $update = "UPDATE users SET username = '$username', nama_lengkap = '$nama_lengkap', role = '$role' WHERE id_user = '$id'";
    }

    if (mysqli_query($conn, $update)) {
        header("Location: tampil.php");
        exit();
    } else {
        $error = "Gagal memperbarui user: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Edit Data User</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($data['username']); ?>" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password (Kosongkan jika tidak diubah)</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password baru">
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']); ?>" required>
            </div>

            <div class="form-group">
                <label for="role">Role / Level</label>
                <select name="role" id="role" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
                    <option value="peminjam" <?= $data['role'] == 'peminjam' ? 'selected' : ''; ?>>Peminjam</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>

</body>
</html>