<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST["id_user"];
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "UPDATE user SET nama=?, email=?, password=? WHERE id_user=?";
    if ($stmt = mysqli_prepare($conection_db, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $password, $id_user);
        if (mysqli_stmt_execute($stmt)) {
            header("location: user.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conection_db);
} else {
    if (isset($_GET["id_user"]) && !empty(trim($_GET["id_user"]))) {
        $id_user = trim($_GET["id_user"]);
        $sql = "SELECT * FROM user WHERE id_user = ?";
        if ($stmt = mysqli_prepare($conection_db, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id_user);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $nama = $row["nama"];
                    $email = $row["email"];
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
    <title>Edit User</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link your CSS here -->
    <style>
        .navbar {
            background-color: #290964;
        }
        
        .header {
            background-color: #290964;
            color: #fff; /* Ubah warna teks header sesuai kebutuhan */
        }
        
        .navbar-brand .b-title {
            color: #fff; /* Ubah warna teks navbar brand sesuai kebutuhan */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2 class="header">Edit User</h2>
        <p>Please edit the input values and submit to update the user record.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $nama; ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            </div>
            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="user.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
