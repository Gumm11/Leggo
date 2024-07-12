<?php
session_start();
session_unset();

// Menghapus cookie sesi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Menghapus cookie user_id dan username
setcookie('user_id', '', time() - 3600, "/");
setcookie('username', '', time() - 3600, "/");

session_destroy();
header("Location: https://localhost/github/leggo/login.php");
exit();
?>