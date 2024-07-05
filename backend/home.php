<?php
// Include config file
include "config.php";

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch data
// $total_contact = $total_tags = $total_products = 0;

// Pagination for contact
// $sql = "SELECT * FROM contact";
// $result = $conection_db->query($sql);
// $number_of_category_results = $result->num_rows;
// $number_of_contact = ceil($number_of_category_results / $results_per_page);
// if (!isset($_GET['contact'])) {
//     $contact = 1;
// } else {
//     $contact = $_GET['contact'];
// }
// $this_category_first_result = ($contact - 1) * $results_per_page;
// $sql = "SELECT * FROM contact LIMIT " . $this_category_first_result . ',' . $results_per_page;
// $result = $conection_db->query($sql);
// $contact = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

// Function to fetch count from a given table
// function getCount($conection_db, $table)
// {
//     $sql = "SELECT COUNT(*) as total FROM " . $table;
//     $result = mysqli_query($conection_db, $sql);
//     if ($result) {
//         $row = mysqli_fetch_assoc($result);
//         return $row['total'];
//     } else {
//         return 0; // or handle error
//     }
// }

// // Define how many results you want per page
// $results_per_page = 4;

// function formatRupiah($number)
// {
//     return 'Rp. ' . number_format($number, 0, ',', '.');
// }

// $total_contact = getCount($conection_db, 'contact');
// $total_tags = getCount($conection_db, 'tags');
// $total_products = getCount($conection_db, 'products');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Jogja</title>

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
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="home.php" class="b-brand">
                    <div>
                        <img class="rounded-circle" style="width:40px;" src="assets/images/Dressclo.ico" alt="activity-user">
                    </div>
                    <span class="b-title">Dressclo</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item active">
                        <a href="home.php" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Travel</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="category.php">Category</a></li>
                            <li><a href="user.php">user</a></li>
                            <li><a href="product.php">Product</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link"><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Account</span></a>
                        <ul class="pcoded-submenu">
                            <li><a href="reset_password.php">Change Password</a></li>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light">
        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
            <a href="home.php" class="b-brand">
                <div>
                    <img class="rounded-circle" style="width:40px;" src="assets/images/Dressclo.ico" alt="activity-user">
                </div>
                <span class="b-title">Admin</span>
            </a>
        </div>
        <a class="mobile-menu" id="mobile-header" href="javascript:">
            <i class="feather icon-more-horizontal"></i>
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li><a href="javascript:" class="full-screen" onclick="javascript:toggleFullScreen()"><i class="feather icon-maximize"></i></a></li>
            </ul>
        </div>
    </header>
    <!-- [ Header ] end -->
    <!-- Main Container -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- Breadcrumb -->
                    <!-- Add your breadcrumb here if needed -->

                    <!-- Main Content -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <h3 class="text-center">Total contact</h3>
                                    <p class="text-center display-4"><?php echo $total_contact; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <h3 class="text-center">Total Tags</h3>
                                    <p class="text-center display-4"><?php echo $total_tags; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card custom-card">
                                <div class="card-body">
                                    <h3 class="text-center">Total Products</h3>
                                    <p class="text-center display-4"><?php echo $total_products; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts table with pagination -->
                    <div class="col-md-13">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="text-center">Pesan Contacts</h3>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID Pesan</th>
                                                <th class="text-center">Nama Depan</th>
                                                <th class="text-center">Nama Belakang</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">No Handphone</th>
                                                <th class="text-center">Pesan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($contact as $index => $category) : ?>
                                                <tr>
                                                    <td class="text-center"><?php echo $index + 1 + ($contact - 1) * $results_per_page; ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($category['name']); ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($category['slug']); ?></td>
                                                    <td class="text-center"><?php echo htmlspecialchars($category['category_count']); ?></td>
                                                    <td class="text-center"><img src="<?php echo htmlspecialchars($category['image']); ?>" alt="Category Image" style="width:40px;"></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <!-- Pagination for contact -->
                                    <?php if ($number_of_contact > 1) : ?>
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">
                                                <?php if ($contact > 1) : ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-dark" href="home.php?contact=<?php echo $contact - 1; ?>" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                    </li>
                                                <?php else : ?>
                                                    <li class="page-item disabled">
                                                        <a class="page-link text-dark" href="#" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                            <span class="sr-only">Previous</span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                <?php for ($i = 1; $i <= $number_of_contact; $i++) : ?>
                                                    <li class="page-item <?php if ($i == $contact) echo 'active'; ?>"><a class="page-link text-dark" href="home.php?contact=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                                                <?php endfor; ?>
                                                <?php if ($contact < $number_of_contact) : ?>
                                                    <li class="page-item">
                                                        <a class="page-link text-dark" href="home.php?contact=<?php echo $contact + 1; ?>" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </li>
                                                <?php else : ?>
                                                    <li class="page-item disabled">
                                                        <a class="page-link text-dark" href="#" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                            <span class="sr-only">Next</span>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Main Content -->
                </div>
            </div>
        </div>
    </div>

    <!-- Required Js -->
    <script src="assets/js/vendor-all.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/pcoded.min.js"></script>

</body>

</html>