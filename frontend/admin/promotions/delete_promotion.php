<?php
// Database connection
require_once '../../../backend/db_connect.php';

// Get promotion ID from URL
$promotion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if the promotion exists
$sql = "SELECT * FROM promotions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $promotion_id);
$stmt->execute();
$result = $stmt->get_result();

// If the promotion doesn't exist, show an error
if ($result->num_rows === 0) {
    echo "Promotion not found.";
    exit;
}

// Delete the promotion
$delete_sql = "DELETE FROM promotions WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $promotion_id);
$delete_stmt->execute();

// Redirect to manage promotions page
header("Location: manage_promotions.php");
exit;
?>
