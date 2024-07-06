<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET["nim"])) {
    $nim = $_GET["nim"];
    echo "NIM: " . $nim . "<br>";

    $sql = "DELETE FROM admin WHERE nim = ?";

    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $nim);

        if (mysqli_stmt_execute($stmt)) {
            echo "Delete successful!<br>";
            header("location: admin.php");
        } else {
            echo "Something went wrong. Please try again later.<br>";
            echo mysqli_error($conection_db);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Could not prepare the statement.<br>";
        echo mysqli_error($conection_db);
    }
    mysqli_close($conection_db);
} else {
    echo "No NIM parameter found.<br>";
}
?>