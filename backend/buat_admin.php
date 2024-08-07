<?php
session_start();

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
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); // Enkripsi password
    }

    // Validasi unggahan file gambar
    if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
        $targetDir = "uploads_admin/"; // Pastikan direktori 'uploads_admin/' ada di lokasi yang benar
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
        $sql_check = "SELECT id FROM admins WHERE nim = ?";
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
            $sql_insert = "INSERT INTO admins (nim, nama, password, gambar) VALUES (?, ?, ?, ?)";
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
                        header("location: admin_list.php");
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

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Jogja</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/TGU.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
.pcoded-navbar {
    background-color: #290964 !important;
}
.pcoded-navbar .navbar-content .nav {
    background-color: #290964;
}
.pcoded-navbar .navbar-content .nav a {
    color: white !important;
}
.card.custom-card .card-body {
    background-color: #290964;
    color: white;
}
.pcoded-header {
    background-color: #290964;
}
.pcoded-header .navbar {
    background-color: #290964;
}
.navbar-brand .b-title {
    color: #ffffff;
}
.pcoded-navbar .navbar-content .pcoded-submenu {
    background-color: #290964 !important;
}
.pcoded-navbar .navbar-content .pcoded-submenu .nav-item .nav-link {
    color: white !important;
}
.pcoded-navbar .nav-item .nav-link {
    color: white !important;
}
.pcoded-navbar .nav-item.active .nav-link {
    background-color: #290964 !important;
}
</style>
</head>

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar">
        <div class="navbar-wrapper">
        <div class="navbar-brand header-logo" style="background-color: #290964;">
            <a href="home.php" class="b-brand">
                <div>
                    <img class="rounded-circle" style="width:40px;" src="assets/images/TGU.ico" alt="activity-user">
                </div>
              <span class="b-title">Travel Jogja</span>
            </a>
             <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigasi</label>
                    </li>
                    <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item active">
                        <a href="home.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Travel</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="user.php">User</a></li>
                            <li><a href="admin.php">Admin</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Account</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
            <a href="home.php" class="b-brand">
                <div>
                    <img class="rounded-circle" style="width:40px;" src="assets/images/TGU.ico" alt="activity-user">
                </div>
                <span class="b-title">Admin</span>
            </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="javascript:">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li><a href="javascript:" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->

    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Add New Admin</h4>
                                    </div>
                                    <div class="card-block">
                                        <form method="post" action="php_buat_admin.php" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="nim">NIM:</label>
                                                <input type="text" class="form-control" id="nim" name="nim" required>
                                                <span class="invalid-feedback"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name:</label>
                                                <input type="text" class="form-control" id="name" name="name" required>
                                                <span class="invalid-feedback"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password:</label>
                                                <input type="password" class="form-control" id="password" name="password" required>
                                                <span class="invalid-feedback"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Profile Image:</label>
                                                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
                                                <span class="invalid-feedback"></span>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Add Admin</button>
                                                <a href="admin_list.php" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>

</body>

</html>
