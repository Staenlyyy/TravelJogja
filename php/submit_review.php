<?php
require_once '../backend/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $komentar = $_POST['komentar'];
    $rating = $_POST['rating'];

    if (!empty($komentar) && is_numeric($rating) && $rating >= 1 && $rating <= 5) {
        $sql = "INSERT INTO komentar (id_user, komentar, rating) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($conection_db, $sql)) {
            $id_user = 1; // Ubah dengan ID user yang benar dari sesi login
            mysqli_stmt_bind_param($stmt, "iss", $id_user, $komentar, $rating);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../templates/submit_berhasil.html");
                exit();
            } else {
                echo "Gagal menyimpan ulasan.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Gagal menyiapkan pernyataan SQL.";
        }
    } else {
        echo "Data ulasan tidak valid.";
    }
}

mysqli_close($conection_db);
?>
