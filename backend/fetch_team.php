<?php
$servername = "localhost";
$username = "root"; // sesuaikan dengan username database Anda
$password = ""; // sesuaikan dengan password database Anda
$dbname = "travel_jogja"; // sesuaikan dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT nim, nama FROM admin";
$result = $conn->query($sql);

$team = array();
if ($result->num_rows > 0) {
    // Mengambil setiap baris data dan menambahkannya ke array
    while($row = $result->fetch_assoc()) {
        $team[] = $row;
    }
} else {
    echo "0 hasil";
}
$conn->close();

// Mengubah array menjadi format JSON
echo json_encode($team);
?>
