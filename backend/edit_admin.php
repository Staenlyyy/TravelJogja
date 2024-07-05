<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST["nim"];
    $nama = $_POST["nama"];
    $gambar = $_POST["gambar"];
    $password = $_POST["password"];

    $sql = "UPDATE admin SET nama=?, gambar=?, password=? WHERE nim=?";
    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $nim, $nama, $gambar, $password);
        if (mysqli_stmt_execute($stmt)) {
            header("location: admin.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conection_db);
} else {
    if (isset($_GET["nim"]) && !empty(trim($_GET["nim"]))) {
        $nim = trim($_GET["nim"]);
        $sql = "SELECT * FROM admin WHERE nim = ?";
        if ($stmt = mysqli_prepare($conection_db, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $nim);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $nim = $row["nim"];
                    $nama = $row["nama"];
                    $gambar = $row["gambar"];
                    $password = $row["password"];
                } else {
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_close($conection_db);
    } else {
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>edit admin</title>
    <!-- Meta, Favicon, and CSS links here -->
</head>
<body>
    <div class="wrapper">
        <h2>edit admin</h2>
        <p>Please edit the input values and submit to update the admin record.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nim</label>
                <input type="text" name="nim" class="form-control" value="<?php echo $nim; ?>">
            </div>
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>">
            </div>
            <div class="form-group">
                <label>Gambar</label>
                <input type="gambar" name="gambar" class="form-control" value="<?php echo $gambar; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            <input type="hidden" name="nim" value="<?php echo $nim; ?>">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="admin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
