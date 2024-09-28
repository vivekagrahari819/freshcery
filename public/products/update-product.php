<?php 

// Redirect if no HTTP referer
if (!isset($_SERVER['HTTP_REFERER'])) {
    // Redirect them to the homepage
    header('Location: http://localhost/freshcery/index.php');
    exit;
}


?>

<?php
require "../includes/db.php"; // Adjust the path as needed

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log request data for debugging
error_log(print_r($_POST, true));

// Check if request is POST and action is update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $pro_qty = $_POST['qty'];

    try {
        // Prepare the SQL statement with placeholders
        $update = $pdo->prepare("UPDATE cart SET pro_qty = :pro_qty WHERE id = :id");

        // Execute the statement with the actual values
        $update->execute([
            ':pro_qty' => $pro_qty,
            ':id' => $id
        ]);

        // Check if the update was successful
        if ($update->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made']);
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
