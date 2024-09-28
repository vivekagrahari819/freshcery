
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




// Fetch products from the cart
$products = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id");
$products->execute(['user_id' => $_SESSION['user_id']]);
$allProducts = $products->fetchAll(PDO::FETCH_OBJ);

$totalAmount = 0; // Initialize total amount

if (isset($_POST['submit'])) {
    // Check if all required fields are filled
    if (
        empty($_POST['name']) || empty($_POST['lname']) || empty($_POST['address']) ||
        empty($_POST['city']) || empty($_POST['country']) || empty($_POST['zip_code']) ||
        empty($_POST['email']) || empty($_POST['phone'])
    ) {
        echo "<script>alert('One or more required fields are empty')</script>";
    } else {
        // Process form data
        $name = htmlspecialchars(trim($_POST['name']));
        $lname = htmlspecialchars(trim($_POST['lname']));
        $company_name = !empty($_POST['company_name']) ? htmlspecialchars(trim($_POST['company_name'])) : null; // Optional
        $address = htmlspecialchars(trim($_POST['address']));
        $city = htmlspecialchars(trim($_POST['city']));
        $country = htmlspecialchars(trim($_POST['country']));
        $zip_code = htmlspecialchars(trim($_POST['zip_code']));
        $email = htmlspecialchars(trim($_POST['email']));
        $phone = htmlspecialchars(trim($_POST['phone']));
        $order_notes = htmlspecialchars(trim($_POST['order_notes']));
        $price = $totalAmount + 20; // Including shipping cost
        $user_id = $_SESSION['user_id'];

        // Insert order into the database
        $stmt = $pdo->prepare("INSERT INTO orders (name, lname, company_name, address, city, country, zip_code, email, phone, order_notes, price, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $lname, $company_name, $address, $city, $country, $zip_code, $email, $phone, $order_notes, $price, $user_id]);

        echo "<script>alert('Order has been created successfully.'); window.location.href='" . APPURL . "products/charge.php';</script>";
    }
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Checkout</h1>
                <p class="lead">Save time and leave the groceries to us.</p>
            </div>
        </div>
    </div>

    <section id="checkout">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-7">
                    <h5 class="mb-3">BILLING DETAILS</h5>
                    <form action="checkout.php" method="POST" class="bill-detail">
                        <fieldset>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Name" type="text" name="name" required>
                                </div>
                                <div class="col">
                                    <input class="form-control" placeholder="Last Name" type="text" name="lname" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Company Name" type="text" name="company_name">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Address" name="address" required></textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Town / City" type="text" name="city" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="State / Country" type="text" name="country" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Postcode / Zip" type="text" name="zip_code" required>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" placeholder="Email Address" type="email" name="email" required>
                                </div>
                                <div class="col">
                                    <input class="form-control" placeholder="Phone Number" type="tel" name="phone" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" placeholder="Order Notes" name="order_notes"></textarea>
                            </div>
                        </fieldset>
                        <button name="submit" type="submit" class="btn btn-primary float-right">PROCEED TO CHECKOUT <i class="fa fa-check"></i></button>
                    </form>
                </div>
                <div class="col-xs-12 col-sm-5">
                    <div class="holder">
                        <h5 class="mb-3">YOUR ORDER</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Products</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($allProducts as $product) : 
                                        $subtotal = $product->pro_price * $product->pro_qty; // Calculate subtotal
                                        $totalAmount += $subtotal; // Add to total amount
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo htmlspecialchars($product->pro_title); ?> x <?php echo htmlspecialchars($product->pro_qty); ?>
                                        </td>
                                        <td class="text-right">
                                            USD <?php echo number_format($subtotal, 2); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Cart Subtotal</strong></td>
                                        <td class="text-right">
                                            USD <?php echo number_format($totalAmount, 2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Shipping</strong></td>
                                        <td class="text-right">
                                            USD 20.00 <!-- Static shipping cost -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>ORDER TOTAL</strong></td>
                                        <td class="text-right">
                                            <strong>USD <?php echo number_format($totalAmount + 20, 2); ?></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <p class="text-right mt-3">
                        <input type="checkbox" required> I’ve read &amp; accept the <a href="#">terms &amp; conditions</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "../includes/footer.php"; ?>
