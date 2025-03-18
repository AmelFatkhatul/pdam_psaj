<?php
session_start();

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi database
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk keamanan
    $sql = "SELECT * FROM login_admin WHERE username_admin = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ada di database
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // Cek apakah password sesuai (HARUS gunakan password_hash() di database)
        if ($password == $admin['password_admin']) {  
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['admin_username'] = $admin['username_admin'];

            // Redirect ke halaman manajemen tagihan
            header("Location: ../dashboard-admin/daftar-anggota.php");
            exit();
        } else {
            $_SESSION['error'] = "Password salah!";
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan!";
    }

    header("Location: ../login/login_admin.php");
    exit();
}

$stmt->close();
$conn->close();
?>
