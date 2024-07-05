<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Buat pernyataan SQL
    $sql = "SELECT * FROM user WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        
        // Eksekusi pernyataan
        $stmt->execute();
        
        // Dapatkan hasil
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($password == $row['password']) {
                // Kata sandi benar, buat sesi
                $_SESSION['loggedin'] = true;
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION['email'] = $row['email'];

                // Arahkan ke halaman index.html
                header("location: ../index.html");
            } else {
                // Kata sandi salah
                echo "Incorrect password.";
            }
        } else {
            // Email tidak ditemukan
            echo "No account found with that email.";
        }

        $stmt->close();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
    $conn->close();
}
?>
