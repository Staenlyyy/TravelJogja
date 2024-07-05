<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
// Include config file
require_once "config.php";
require_once "php_login.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dressclo</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon icon -->
    <link rel="icon" href="assets/images/Dressclo.ico" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/fontawesome-all.min.css">
    <!-- animation css -->
    <link rel="stylesheet" href="assets/plugins/animation/css/animate.min.css">
    <!-- vendor css -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-content">
            <div class="auth-bg">
                <span class="r"></span>
                <span class="r s"></span>
                <span class="r s"></span>
                <span class="r"></span>
            </div>
            <div class="card">
                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="feather icon-unlock auth-icon"></i>
                        </div>
                        <h3 class="mb-4">Login</h3>
                        <div class="input-group mb-3 <?= (!empty($email_err)) ? 'has-error' : ''; ?>">
                            <input type="email" class="form-control" name="email" placeholder="Email" value="<?= $email; ?>">
                        </div>
                        <span class="help-block"><?= $email_err; ?></span>
                        <div class="input-group mb-4 <?= (!empty($password_err)) ? 'has-error' : ''; ?>">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <span class="help-block"><?= $password_err; ?></span>
                        <div class="form-group text-left">
                            <div class="checkbox checkbox-fill d-inline">
                                <input type="checkbox" id="show_password">
                                <label for="show_password" class="cr"> Show Password</label>
                            </div>
                        </div>
                        <button class="btn btn-primary shadow-2 mb-4">Login</button>
                        <p class="mb-0 text-muted">Don’t have an account? <a href="register.php">Signup</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('show_password').addEventListener('change', function() {
            var passwordInput = document.getElementById('password');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
            
        });
    </script>
</body>
</html>
