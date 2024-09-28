<?php
require "../includes/header.php";
require "../includes/db.php";

// Check if the session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "';</script>";
    exit();
}

// Check if the `id` is provided and matches the logged-in user
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($id !== (int)$_SESSION['user_id']) {
        echo "<script>window.location.href='" . APPURL . "';</script>";
        exit();
    }
} else {
    echo "<script>window.location.href='" . APPURL . "/404.php';</script>";
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postcode = $_POST['postcode'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    // Update the user's details in the database
    $stmt = $pdo->prepare("UPDATE users 
                           SET fullname = :name, 
                               address = :address, 
                               city = :city, 
                               state = :state, 
                               postcode = :postcode, 
                               email = :email, 
                               phone = :phone, 
                               mypassword = :password 
                           WHERE id = :id");
    $stmt->execute([
        ':name' => $fullName,
        ':address' => $address,
        ':city' => $city,
        ':state' => $state,
        ':postcode' => $postcode,
        ':email' => $email,
        ':phone' => $phone,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':id' => $id
    ]);

    echo "<script>alert('Account details updated successfully!');</script>";
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL; ?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Settings</h1>
                <p class="lead">Update Your Account Info</p>
            </div>
        </div>
    </div>

    <section id="checkout">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xs-12 col-sm-6">
                    <h5 class="mb-3">ACCOUNT DETAILS</h5>
                    <form action="setting.php?id=<?php echo $id; ?>" method="POST" class="bill-detail">
                        <fieldset>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" name="fullname" placeholder="Full Name" type="text" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="address" placeholder="Address" required></textarea>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="city" placeholder="Town / City" type="text" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="state" placeholder="State / Country" type="text" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="postcode" placeholder="Postcode / Zip" type="text" required>
                            </div>
                            <div class="form-group row">
                                <div class="col">
                                    <input class="form-control" name="email" placeholder="Email Address" type="email" required>
                                </div>
                                <div class="col">
                                    <input class="form-control" name="phone" placeholder="Phone Number" type="tel" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="password" placeholder="Password" type="password" required>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                                <div class="clearfix"></div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "../includes/footer.php"; ?>