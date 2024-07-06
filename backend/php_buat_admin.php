<?php
session_start();

// Tampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include config.php atau file lain di mana Anda memiliki koneksi database disetup
include('config.php');

// Periksa apakah pengguna sudah login, jika belum maka arahkan ke halaman login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Inisialisasi variabel dengan nilai awal kosong
$nim = $name = $password = $image = "";
$nim_err = $name_err = $password_err = $image_err = "";

// Memproses data formulir ketika formulir dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi NIM
    if (empty(trim($_POST["nim"]))) {
        $nim_err = "Silakan masukkan NIM.";
    } else {
        $nim = trim($_POST["nim"]);
    }

    // Validasi nama
    if (empty(trim($_POST["name"]))) {
        $name_err = "Silakan masukkan nama.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validasi password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Silakan masukkan password.";
    } else {
        $password = trim($_POST["password"]); // Tidak ada enkripsi password
    }

    // Validasi unggahan file gambar
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $targetDir = "uploads_admin/"; // Pastikan direktori 'uploads_admin/' ada di lokasi yang benar
        // Buat direktori jika belum ada
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Periksa apakah file adalah gambar yang valid
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Izinkan hanya format file tertentu
            $allowedFormats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowedFormats)) {
                $image_err = "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            }
        } else {
            $image_err = "File bukan gambar.";
        }
    } else {
        $image_err = "Silakan pilih file gambar.";
    }

    // Jika tidak ada kesalahan input, masukkan data ke database
    if (empty($nim_err) && empty($name_err) && empty($password_err) && empty($image_err)) {
        // Periksa ketersediaan NIM di database
        $sql_check = "SELECT nim FROM admin WHERE nim = ?";
        if ($stmt_check = mysqli_prepare($conection_db, $sql_check)) {
            mysqli_stmt_bind_param($stmt_check, "s", $param_nim);
            $param_nim = $nim;
            if (mysqli_stmt_execute($stmt_check)) {
                mysqli_stmt_store_result($stmt_check);
                if (mysqli_stmt_num_rows($stmt_check) > 0) {
                    $nim_err = "NIM sudah ada.";
                }
            } else {
                echo "Oops! Terjadi kesalahan. Silakan coba lagi nanti.";
            }
            mysqli_stmt_close($stmt_check);
        }

        // Jika tidak ada error, masukkan data ke database
        if (empty($nim_err)) {
            // Persiapkan pernyataan insert
            $sql_insert = "INSERT INTO admin (nim, nama, password, gambar) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = mysqli_prepare($conection_db, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "ssss", $param_nim, $param_name, $param_password, $param_image);
                $param_nim = $nim;
                $param_name = $name;
                $param_password = $password;
                $param_image = $targetFile;

                if (mysqli_stmt_execute($stmt_insert)) {
                    // Pindahkan file yang diunggah ke direktori tujuan
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        // Redirect ke halaman daftar admin
                        header("location: admin.php");
                        exit;
                    } else {
                        $image_err = "Maaf, terjadi kesalahan saat mengunggah file Anda.";
                    }
                } else {
                    echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
                }
                mysqli_stmt_close($stmt_insert);
            } else {
                echo "Ups! Ada yang salah. Silakan coba lagi nanti.";
            }
        }
    }

    // Tutup koneksi jika masih terbuka
    if (isset($conection_db)) {
        mysqli_close($conection_db);
    }
}
?>