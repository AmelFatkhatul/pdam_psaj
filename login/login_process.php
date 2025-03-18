<?php
session_start(); // Memulai sesi

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pdam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan username dan password dikirim dari form
if (isset($_POST['username_user']) && isset($_POST['password_user'])) {
    $username_user = $_POST['username_user'];
    $password_user = $_POST['password_user'];

    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM login_users WHERE username_user = ?");
    $stmt->bind_param("s", $username_user);
    $stmt->execute();
    $result = $stmt->get_result();

    // Cek apakah username ada di database
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password_user, $row['password_user'])) {
            $_SESSION['username_user'] = $row['username_user']; // Simpan session username
            $_SESSION['email_user'] = $row['email_user']; // Simpan session email

            header('Location: ../dashboard-user/user.php');
            exit();
        } else {
            $_SESSION['error'] = "Password salah!"; // Simpan error di session
            header("Location: ../login/login_user.php"); // Redirect kembali ke login
            exit();
        }
    } else {
        $_SESSION['error'] = "Username tidak ditemukan, daftar?"; // Simpan error di session
        header("Location: ../login/login_user.php"); // Redirect kembali ke login
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
