<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_jogja";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari tabel admin
$sql = "SELECT nim, nama, gambar FROM admin";
$result = $conn->query($sql);

$admin_data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $admin_data[] = $row;
    }
}
$conn->close();

header('Content-Type: application/json');
echo json_encode($admin_data);
?>