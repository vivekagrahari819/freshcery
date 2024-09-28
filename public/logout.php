<?php
// Check if the session is active before starting it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the homepage
header("Location: /freshcery/auth/login.php");
exit();
?>
