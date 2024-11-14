<?php
// Start the session
session_start();

// Destroy the session data
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to login page
header("Location: ../login.php");
exit;
?>
