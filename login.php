<?php
session_start();
include 'connection.php';

// Jika user sudah login, langsung arahkan ke home.php
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

$koneksi = isset($conn) ? $conn : (isset($con) ? $con : null);
$mode    = isset($_GET['mode']) ? $_GET['mode'] : 'login';

$error_reg   = '';
$success_reg = '';

// PROSES REGISTER
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'register') {
    $mode         = 'register';
    $username     = mysqli_real_escape_string($koneksi, trim($_POST['username_reg']));
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_reg']));
    $password     = $_POST['password_reg'];
    $confirm_pw   = $_POST['confirm_password_reg'];
    $role         = 'peminjam'; // Role otomatis peminjam/siswa

    if (empty($username) || empty($nama_lengkap) || empty($password)) {
        $error_reg = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_pw) {
        $error_reg = "Konfirmasi password tidak cocok!";
    } else {
        // Cek username di tabel users
        $query_cek = "SELECT * FROM users WHERE username = '$username'";
        $res_cek   = mysqli_query($koneksi, $query_cek);

        if ($res_cek && mysqli_num_rows($res_cek) > 0) {
            $error_reg = "Username sudah terdaftar! Gunakan username lain.";
        } else {
            // Simpan ke tabel users
            $query_reg = "INSERT INTO users (username, password, nama_lengkap, role) 
                          VALUES ('$username', '$password', '$nama_lengkap', '$role')";

            if (mysqli_query($koneksi, $query_reg)) {
                $success_reg = "Pendaftaran berhasil! Silakan login di bawah.";
                $mode = 'login';
            } else {
                $error_reg = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($mode == 'register') ? 'Daftar' : 'Login'; ?> - Peminjaman Alat</title>
    <link rel="stylesheet" href="from.css">
    <style>
        .toggle-box {
            text-align: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .toggle-btn {
            color: #0284c7;
            font-weight: bold;
            text-decoration: none;
        }
        .toggle-btn:hover {
            text-decoration: underline;
        }
        .alert-box {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: center;
        }
        .alert-error {
            background-color: #fca5a5;
            color: #991b1b;
        }
        .alert-success {
            background-color: #86efac;
            color: #166534;
        }
    </style>
</head>
<body>

    <div class="form-container">

        <?php if ($mode == 'login'): ?>
            <!-- FORM LOGIN -->
            <div class="login-icon">🔐</div>
            <h2>Aplikasi Peminjaman</h2>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
                <div class="alert-box alert-error">
                    Username atau password salah!
                </div>
            <?php endif; ?>

            <?php if (!empty($success_reg)): ?>
                <div class="alert-box alert-success">
                    <?php echo $success_reg; ?>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <div class="toggle-box">
                Belum memiliki akun? <a href="login.php?mode=register" class="toggle-btn">Daftar Sekarang</a>
            </div>

        <?php else: ?>
            <!-- FORM REGISTER -->
            <div class="login-icon">📝</div>
            <h2>Daftar Akun Peminjam</h2>

            <?php if (!empty($error_reg)): ?>
                <div class="alert-box alert-error">
                    <?php echo $error_reg; ?>
                </div>
            <?php endif; ?>

            <form action="login.php?mode=register" method="POST">
                <input type="hidden" name="action" value="register">

                <div class="form-group">
                    <label for="nama_reg">Nama Lengkap</label>
                    <input type="text" id="nama_reg" name="nama_reg" placeholder="Masukkan nama lengkap" value="<?php echo isset($_POST['nama_reg']) ? htmlspecialchars($_POST['nama_reg']) : ''; ?>" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="username_reg">Username</label>
                    <input type="text" id="username_reg" name="username_reg" placeholder="Buat username" value="<?php echo isset($_POST['username_reg']) ? htmlspecialchars($_POST['username_reg']) : ''; ?>" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="password_reg">Password</label>
                    <input type="password" id="password_reg" name="password_reg" placeholder="Buat password" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password_reg">Konfirmasi Password</label>
                    <input type="password" id="confirm_password_reg" name="confirm_password_reg" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>

            <div class="toggle-box">
                Sudah punya akun? <a href="login.php?mode=login" class="toggle-btn">Masuk di Sini</a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>