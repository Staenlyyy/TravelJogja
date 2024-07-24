<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

// Aktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $password = $_POST["password"];

    // Debugging output
    echo "Received POST data: NIM = $nim, Nama = $nama, Password = $password<br>";

    $targetDir = "uploads_admin/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $uploadOk = 1;
    $targetFile = $targetDir . basename($_FILES["gambar"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $isImageUploaded = false;

    if (!empty($_FILES["gambar"]["name"])) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.<br>";
            $uploadOk = 0;
        }

        if (file_exists($targetFile)) {
            echo "Sorry, file already exists.<br>";
            $uploadOk = 0;
        }

        if ($_FILES["gambar"]["size"] > 500000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $targetFile)) {
                echo "The file " . htmlspecialchars(basename($_FILES["gambar"]["name"])) . " has been uploaded.<br>";
                $isImageUploaded = true;
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }
    }

    if ($isImageUploaded) {
        $sql = "UPDATE admin SET nama=?, gambar=?, password=? WHERE nim=?";
    } else {
        $sql = "UPDATE admin SET nama=?, password=? WHERE nim=?";
    }

    // Debugging output
    echo "Preparing to execute query: $sql<br>";
    echo "Values: Nama = $nama, Password = $password, NIM = $nim<br>";
    if ($isImageUploaded) {
        echo "Image uploaded at: $targetFile<br>";
    }

    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        if ($isImageUploaded) {
            mysqli_stmt_bind_param($stmt, "sssi", $nama, $targetFile, $password, $nim);
        } else {
            mysqli_stmt_bind_param($stmt, "ssi", $nama, $password, $nim);
        }

        echo "Executing query...<br>";

        if (mysqli_stmt_execute($stmt)) {
            echo "Update successful!<br>";
            header("location: admin.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.<br>";
            echo "Error: " . mysqli_stmt_error($stmt) . "<br>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Could not prepare the statement.<br>";
        echo "Error: " . mysqli_error($conection_db) . "<br>";
    }
    mysqli_close($conection_db);
} else {
    if (isset($_GET["nim"])) {
        $nim = $_GET["nim"];
        echo "NIM: " . $nim . "<br>";

        $sql = "SELECT * FROM admin WHERE nim = ?";
        if ($stmt = mysqli_prepare($conection_db, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $nim);

            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $admin = mysqli_fetch_assoc($result);
                    // Debugging output
                    echo "Admin data retrieved: " . json_encode($admin) . "<br>";
                } else {
                    echo "No records matching your query were found.<br>";
                }
            } else {
                echo "Something went wrong. Please try again later.<br>";
                echo "Error: " . mysqli_stmt_error($stmt) . "<br>";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Could not prepare the statement.<br>";
            echo "Error: " . mysqli_error($conection_db) . "<br>";
        }
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
    </style>
</head>
<body>
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <nav class="pcoded-navbar">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="home.php" class="b-brand">
                    <div>
                        <img class="rounded-circle" style="width:40px;" src="assets/images/TGU.ico" alt="activity-user">
                    </div>
                    <span class="b-title">DIY</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigasi</label>
                    </li>
                    <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item">
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
    <header class="navbar pcoded-header navbar-expand-lg navbar-light">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
            <a href="home.php" class="b-brand">
                <div>
                    <img class="rounded-circle" style="width:40px;" src="assets/images/TGU.ico" alt="activity-user">
                </div>
                <span class="b-title">DIY</span>
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
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Edit Admin</h4>
                                    </div>
                                    <div class="card-block">
                                        <form action="edit_admin.php" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="nim">NIM</label>
                                                <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($admin['nim']); ?>" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label for="nama">Nama</label>
                                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($admin['nama']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($admin['password']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="gambar">Gambar</label>
                                                <input type="file" class="form-control-file" id="gambar" name="gambar">
                                                <?php if (!empty($admin['gambar'])) : ?>
                                                    <div class="mt-2">
                                                        <label for="current_image">Gambar Saat Ini:</label><br>
                                                        <img src="<?php echo htmlspecialchars($admin['gambar']); ?>" alt="Current Image" style="max-width: 200px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <a href="admin.php" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="styleSelector"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>
</body>
</html>
