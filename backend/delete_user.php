<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

if (isset($_GET["id_user"]) && !empty(trim($_GET["id_user"]))) {
    $id_user = trim($_GET["id_user"]);

    $sql = "DELETE FROM user WHERE id_user = ?";
    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id_user);
        if (mysqli_stmt_execute($stmt)) {
            header("location: user.php");
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
