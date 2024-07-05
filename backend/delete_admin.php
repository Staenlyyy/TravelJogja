<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

if (isset($_GET["nim"]) && !empty(trim($_GET["nim"]))) {
    $nim = trim($_GET["nim"]);

    $sql = "DELETE FROM admin WHERE nim = ?";
    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $nim);
        if (mysqli_stmt_execute($stmt)) {
            header("location: admin.php");
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
?>
