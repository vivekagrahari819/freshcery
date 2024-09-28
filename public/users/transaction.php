<?php 
require "../includes/header.php";

// Check if session has already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize $data to an empty array
$data = [];

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "';</script>";
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Prepare and execute the query to fetch all orders for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    $data = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    echo "<p>Error retrieving data.</p>";
}

?>

<div id="page-content" class="page-content">
    <div class="banner">
        <div class="jumbotron jumbotron-bg text-center rounded-0" style="background-image: url('<?php echo APPURL;?>/assets/img/bg-header.jpg');">
            <div class="container">
                <h1 class="pt-5">
                    Your Transactions
                </h1>
                <p class="lead">
                    Save time and leave the groceries to us.
                </p>
            </div> 
        </div>
    </div>

    <section id="cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data)) : ?>
                                    <?php foreach ($data as $order) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($order->id); ?></td>
                                        <td><?php echo htmlspecialchars($order->name); ?></td>
                                        <td><?php echo htmlspecialchars($order->created_at); ?></td>
                                        <td><?php echo htmlspecialchars($order->price); ?>$</td>
                                        <td><?php echo htmlspecialchars($order->status); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="6">No transactions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require "../includes/footer.php"; ?>
