<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: memberlogin.html"); // Redirect to login page if not logged in
    exit();
}

// Display user-specific content
echo "Welcome, " . htmlspecialchars($_SESSION['user_name']) . "!";
?>
