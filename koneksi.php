<!-- koneksi.php -->


<?php
$servername = "localhost";  // Sesuaikan dengan server
$username = "root";         // Sesuaikan dengan username
$password = "";             // Sesuaikan dengan password
$database = "pdam";         // Sesuaikan dengan nama database

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
