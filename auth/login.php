<?php require "../includes/header.php"; ?>
<?php require "../includes/db.php"; ?>

<?php
// session_start();

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in both fields');</script>";
    } else {
        try {
            // Prepare an SQL statement to fetch the user details
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            // Verify that the user exists and the password is correct
            if ($user && password_verify($password, $user['mypassword'])) {
                // Start the session and store user data
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to index or any other protected page
                echo "<script>alert('Login successful!'); window.location.href = '/freshcery/index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Invalid username or password');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('An error occurred: " . $e->getMessage() . "');</script>";
        }
    }
}
?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL;?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">Login Page</h1>
                <p class="lead">Save time and leave the groceries to us.</p>

                <div class="card card-login mb-5">
                    <div class="card-body">
                        <form class="form-horizontal" action="login.php" method="POST">
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <input class="form-control" type="text" name="username" required placeholder="Username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <input class="form-control" type="password" name="password" required placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" name="login" class="btn btn-primary btn-block text-uppercase">Log In</button>
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
