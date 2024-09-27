
<?php 

// Redirect if no HTTP referer
if (!isset($_SERVER['HTTP_REFERER'])) {
    // Redirect them to the homepage
    header('Location: http://localhost/freshcery/index.php');
    exit;
}


?>


<?php
require "../includes/header.php";
require "../includes/db.php";

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if(isset($_SESSION['user_id'])) {
    // Prepare and execute the DELETE query safely using placeholders
    $delete = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $delete->execute(['user_id' => $_SESSION['user_id']]);

    // Optional: Add some feedback for successful deletion
    $message = "Your cart has been successfully cleared after payment.";
} else {
    // If user is not logged in, redirect to the login page or display an error
    $message = "Error: You are not logged in!";
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Payment Successful</h1>
                <p class="lead">Save time and leave the groceries to us.</p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <!-- Display payment success message -->
                            <h1>Payment successful</h1>
                            <p><?php echo $message; ?></p>
                         <a href="<?php echo APPURL; ?>/freshcart/index.php"  > <button  class="btn-insert mt-3 btn btn-primary btn-lg"><i class="fa fa-shopping-basket"></i>back to Home </button></a>
                          
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "../includes/footer.php";
?>
