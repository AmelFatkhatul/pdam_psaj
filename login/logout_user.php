<?php
session_start(); // Mulai sesi
session_destroy(); // Hapus sesi
header('Location: ../login/login_user.php'); // Redirect ke halaman login
exit;
?>
