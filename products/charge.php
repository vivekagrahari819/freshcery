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

// Initialize totalAmount
$totalAmount = 0;

// Fetch products from the cart
$products = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$products->execute(['user_id' => $_SESSION['user_id']]);
$allProducts = $products->fetchAll(PDO::FETCH_OBJ);

// Calculate the total amount
foreach ($allProducts as $product) {
    $subtotal = $product->pro_price * $product->pro_qty;
    $totalAmount += $subtotal;
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL;?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Pay with Paypal</h1>
                <p class="lead">Save time and leave the groceries to us.</p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" action="login.php" method="POST">

                            <!-- PayPal Button -->
                            <script src="https://www.paypal.com/sdk/js?client-id=Aav4yoC5yigNv1oa3D7byptVaxOLrRLnxBKI_xce2DxCbWiXpOfTVHxpLlZ32cnrrpXHv_0SB5MxQdQe&currency=USD"></script>
                            <!-- Set up a container element for the button -->
                            <div id="paypal-button-container"></div>
                            <script>
                                paypal.Buttons({
                                    // Sets up the transaction when a payment button is clicked
                                    createOrder: (data, actions) => {
                                        return actions.order.create({
                                            purchase_units: [{
                                                amount: {
                                                    value: '<?php echo number_format($totalAmount + 20, 2); ?>' // Total amount including shipping
                                                }
                                            }]
                                        });
                                    },
                                    // Finalize the transaction after payer approval
                                    onApprove: (data, actions) => {
                                        return actions.order.capture().then(function(orderData) {
                                            // Redirect on successful payment
                                            window.location.href = '/freshcery/products/success.php';
                                        });
                                    }
                                }).render('#paypal-button-container');
                            </script>
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
