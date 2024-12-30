<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to home screen
header("Location: /project_WAD/index.php"); // Adjust the path to your home screen file
exit(); // Ensure no further code is executed
?>
