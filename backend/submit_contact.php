<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$first_name = $last_name = $email = $phone = $message = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // // Debugging code
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    // exit; // Tambahkan exit agar kode berikutnya tidak dieksekusi selama debugging

    $first_name = trim($_POST["first-name"]);
    $last_name = trim($_POST["last-name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $message = trim($_POST["message"]);

    // Prepare an insert statement
    $sql = "INSERT INTO contact (nama_depan, nama_belakang, email, no_hp, pesan) VALUES (?, ?, ?, ?, ?)";
    
    if($stmt = mysqli_prepare($conection_db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssss", $first_name, $last_name, $email, $phone, $message);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records created successfully. Redirect to landing page
            header("location: ../templates/submit_berhasil.html");
            exit();
        } else{
            echo "Something went wrong. Please try again later.";
        }
    } else {
        echo "ERROR: Could not prepare query: $sql. " . mysqli_error($conection_db);
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conection_db);
?>
