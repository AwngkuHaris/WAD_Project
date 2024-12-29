<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Get activity ID from URL
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

// Check if the activity exists
$sql = "SELECT * FROM activities WHERE activity_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Activity not found.";
    exit;
}

if ($result->num_rows > 0) {
    // Delete the activity
    $delete_sql = "DELETE FROM activities WHERE activity_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $activity_id);
    $delete_stmt->execute();
}

// Redirect to manage activities page
header("Location: manage_activities.php");
exit;
?>
