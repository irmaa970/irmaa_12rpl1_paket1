<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $password     = mysqli_real_escape_string($conn, $_POST['password']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $role         = mysqli_real_escape_string($conn, $_POST['role']);

    // Cek apakah username sudah dipakai
    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username sudah digunakan! Pilih username lain.";
    } else {
        $query = "INSERT INTO users (username, password, nama_lengkap, role) 
                  VALUES ('$username', '$password', '$nama_lengkap', '$role')";

        if (mysqli_query($conn, $query)) {
            header("Location: tampil.php");
            exit();
        } else {
            $error = "Gagal menambah user: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
    <link rel="stylesheet" href="../from.css">
</head>
<body>

    <div class="form-container">
        <h2>Tambah User Baru</h2>

        <?php if (isset($error)): ?>
            <p style="color: red; text-align: center; margin-bottom: 10px;"><?= $error; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="role">Role / Level</label>
                <select name="role" id="role" style="width: 100%; padding: 12px; border: 2px solid #bae6fd; border-radius: 11px;" required>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                    <option value="peminjam">Peminjam</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Simpan User</button>
        </form>
    </div>

</body>
</html>