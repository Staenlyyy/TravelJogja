<?php
// Mulai sesi
session_start();

// Include file konfigurasi database
include('config.php');

// Ambil data dari form
$email = $_POST['email'];
$password = $_POST['password'];

// Fungsi untuk mendekripsi data
function decrypt($data) {
    $key = 'my_secret_key'; // Gantilah dengan kunci rahasia yang digunakan untuk enkripsi
    $method = 'AES-256-CBC'; // Metode enkripsi yang digunakan
    $iv = substr(hash('sha256', $key), 0, 16); // Inisialisasi vektor

    $decrypted_data = openssl_decrypt(base64_decode($data), $method, $key, 0, $iv);
    return $decrypted_data;
}

// Query untuk mengambil data pengguna dari database
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Jika pengguna ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Dekripsi password yang tersimpan
    $decrypted_password = decrypt($row['password']);
    // Verifikasi password
    if ($password === $decrypted_password) {
        // Set session
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['nama'] = $row['nama'];
        // Redirect ke halaman dashboard atau halaman yang diinginkan
        header("Location: ../index.html");
        exit();
    } else {
        // Password salah
        echo "Password salah!";
    }
} else {
    // Pengguna tidak ditemukan
    echo "Pengguna tidak ditemukan!";
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>
