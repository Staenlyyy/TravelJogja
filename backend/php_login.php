<?php
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Database credentials
$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'traveljogja';

// Create connection
$conection_db = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if($conection_db->connect_error) {
    die("Connection failed: " . $conection_db->connect_error);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if email is empty
    if(empty(trim($_POST["email"])))
    {
        $email_err = "Please enter email.";
    }
    else
    {
        $email = trim($_POST["email"]);
    }
    // Check if password is empty
    if(empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
    }
    else
    {
        $password = trim($_POST["password"]);
    }
    // Validate credentials
    if(empty($email_err) && empty($password_err))
    {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";
        if($stmt = $conection_db->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_email);
            
            // Set parameters
            $param_email = $email;
            // Attempt to execute the prepared statement
            if($stmt->execute())
            {
                // Store result
                $stmt->store_result();
                // Check if email exists, if yes then verify password
                if($stmt->num_rows == 1)
                {                    
                    // Bind result variables
                    $stmt->bind_result($id, $email, $hashed_password);
                    if($stmt->fetch())
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // Password is correct, so start a new session
                            session_start();
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            // Redirect user to welcome page
                            header("location: home.php");
                        }
                        else
                        {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                }
                else
                {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            }
            else
            {
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            $stmt->close();
        }
    }
    // Close connection
    $conection_db->close();
}
?>
