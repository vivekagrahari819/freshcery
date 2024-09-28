<?php require "../includes/header.php"; ?>
<?php require "../includes/db.php"; ?>

<?php
if (isset($_POST['submit'])) {

    // Check for empty fields
    if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['username'])) {
        echo "<script>alert('One or more input fields are empty');</script>";
    } else {
        // Check if passwords match
        if ($_POST['password'] === $_POST['confirm_password']) {

            // Sanitize user input
            $fullname = htmlspecialchars($_POST['fullname']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $mypassword = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
            $username = htmlspecialchars($_POST['username']);
            $image = "user.png"; // Default image

            // Handle image upload
            if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] == 0) {
                $image_tmp = $_FILES['user_image']['tmp_name'];
                $image_name = $_FILES['user_image']['name'];
                $image_size = $_FILES['user_image']['size'];
                $image_type = $_FILES['user_image']['type'];
                $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

                // Validate image type
                $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($image_extension), $valid_extensions)) {
                    // Define a unique name for the image
                    $image = uniqid("IMG_", true) . '.' . $image_extension;
                    // Move the uploaded image to the desired directory
                    move_uploaded_file($image_tmp, "../uploads/" . $image);
                } else {
                    echo "<script>alert('Invalid image file format');</script>";
                    exit();
                }
            }

            try {
                // Prepare an SQL statement to insert the data into the users table
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, mypassword, username, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$fullname, $email, $mypassword, $username, $image]);

                // Redirect to login page after successful registration
                echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
                exit();

            } catch (PDOException $e) {
                echo "<script>alert('An error occurred while registering: " . $e->getMessage() . "');</script>";
            }

        } else {
            echo "<script>alert('Passwords do not match');</script>";
        }
    }
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL;?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Register Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" action="register.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="fullname" required placeholder="Full Name">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="email" name="email" required placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="username" required placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="file" name="user_image" accept="image/*" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="confirm_password" required placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="checkbox">
                                        <input id="checkbox0" type="checkbox" name="terms">
                                        <label for="checkbox0" class="mb-0">I Agree with <a href="terms.html" class="text-light">Terms & Conditions</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" name="submit" class="btn btn-primary btn-block text-uppercase">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>
