<?php 
session_start(); // Start the session

define("APPURL", "http://localhost/freshcery/");

// Ensure the database connection is included
require_once __DIR__ . "/db.php"; // Adjust the path if necessary

// Initialize cart count
$cartCount = 0;

if (isset($_SESSION['user_id'])) {
    // Prepare SQL statement to fetch the number of items in the user's cart
    $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $cartCount = $stmt->fetchColumn(); // Fetch the count directly
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Freshcery | Groceries Organic Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="<?php echo APPURL;?>/assets/fonts/sb-bistro/sb-bistro.css" rel="stylesheet" type="text/css">
    <link href="<?php echo APPURL;?>/assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/o2system-ui/o2system-ui.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/owl-carousel/owl-carousel.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/cloudzoom/cloudzoom.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/thumbelina/thumbelina.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/packages/bootstrap-touchspin/bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo APPURL;?>/assets/css/theme.css">
</head>
<body>
    <div class="page-header">
        <!--=============== Navbar ===============-->
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-transparent" id="page-navigation">
            <div class="container">
                <!-- Navbar Brand -->
                <a href="/freshcery/index.php" class="navbar-brand">
                    <img src="<?php echo APPURL;?>/assets/img/logo/logo.png" alt="">
                </a>

                <!-- Toggle Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarcollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarcollapse">
                    <!-- Navbar Menu -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a href="/freshcery/shop.php" class="nav-link">Shop</a>
                        </li>
                        <?php if(isset($_SESSION['username'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar-header"><img src="<?php echo APPURL;?>/assets/img/logo/avatar.jpg"></div> 
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo APPURL;?>users/transaction.php?id=<?php echo $_SESSION[ 'user_id']; ?> ">Transactions History</a>
                                    <a class="dropdown-item" href="<?php echo APPURL;?>users/setting.php?id=<?php echo $_SESSION[ 'user_id']; ?>">Settings</a>
                                    <a class="dropdown-item" href="/freshcery/logout.php">Logout</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a href="/freshcery/auth/register.php" class="nav-link">Register</a>
                            </li>
                            <li class="nav-item">
                                <a href="/freshcery/auth/login.php" class="nav-link">Login</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/freshcery/products/cart.php" class="nav-link">
                                <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary"><?php echo $cartCount; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</body>
</html>
