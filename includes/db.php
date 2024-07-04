<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_jogja";

//Membuat Koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

//Mengecek Koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>