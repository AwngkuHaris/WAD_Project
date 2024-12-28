<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, access session data
    echo "Welcome, " . $_SESSION['fullName']; // Display the username
    // You can access other session variables like $_SESSION['email'], etc.
} else {
    echo "You are not logged in.";
}
?>
