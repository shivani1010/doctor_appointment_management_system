<?php
session_start(); // Start the session

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login or home page after logout
header("Location: ../index.php"); // Change 'index.php' to your login page
exit();
?>
