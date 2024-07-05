<?php
// Define variables and initialize with empty values
$nim = $password = "";
$nim_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if nim is empty
    if (empty(trim($_POST["nim"]))) {
        $nim_err = "Please enter NIM.";
    } else {
        $nim = trim($_POST["nim"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($nim_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT nim, password FROM admin WHERE nim = ?";
        if ($stmt = mysqli_prepare($conection_db, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_nim);
            
            // Set parameters
            $param_nim = $nim;
            
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if nim exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $nim, $stored_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if ($password == $stored_password) {
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["nim"] = $nim;
                            
                            // Redirect user to welcome page
                            header("location: home.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if nim doesn't exist
                    $nim_err = "No account found with that NIM.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($conection_db);
}
?>
