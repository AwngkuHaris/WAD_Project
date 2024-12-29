<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Get service ID from URL
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($service_id > 0) {
    // Delete service
    $query = "DELETE FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        echo "Service deleted successfully.";
        header("Location: /project_wad/frontend/admin/services/services.php");
        exit;
    } else {
        echo "Error deleting service.";
    }
} else {
    echo "Invalid service ID.";
}
?>
