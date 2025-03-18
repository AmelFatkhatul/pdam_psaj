<?php
$servername = "localhost";
$username = "root";  
$password = "";      
$dbname = "pdam";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$email_user = $_POST['email_user'];
$username_user = $_POST['username_user'];
$password_user = password_hash($_POST['password_user'], PASSWORD_DEFAULT);

// Menyimpan data ke database
$sql = "INSERT INTO login_users (email_user, username_user, password_user) VALUES ('$email_user', '$username_user', '$password_user')";

if ($conn->query($sql) === TRUE) {
    header('Location: ../login/login_user.php');
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
