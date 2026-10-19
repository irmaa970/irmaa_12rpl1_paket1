<?php
session_start();

// 1. Hapus semua variabel session
$_SESSION = array();

// 2. Hapus cookie session jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Hancurkan session
session_destroy();

// 4. Mencegah cache browser
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// 5. Arahkan kembali ke halaman login
header("Location: login.php");
exit();
?>