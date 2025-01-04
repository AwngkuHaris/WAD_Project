<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Check if the user is registered or unregistered
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!isset($_SESSION['user_identifier'])) {
    $_SESSION['user_identifier'] = uniqid('guest_', true);
}
$user_identifier = $_SESSION['user_identifier'];

// Get service_id and quantity from the request
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$quantity = isset($_GET['quantity']) ? intval($_GET['quantity']) : 1;

if ($service_id > 0 && $quantity > 0) {
    // Check if the service exists
    $query = "SELECT * FROM services WHERE service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();

    if ($service) {
        // Insert into cart for registered or unregistered users
        $query = "
            INSERT INTO cart (user_id, user_identifier, service_id, quantity, status, added_at) 
            VALUES (?, ?, ?, ?, 'unpaid', NOW())
            ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isii", $user_id, $user_identifier, $service_id, $quantity);

        if ($stmt->execute()) {
            echo "<script>alert('Service added to cart successfully!'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
        } else {
            echo "<script>alert('Failed to add service to cart.'); window.location.href = '/project_wad/frontend/services/services.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Service not found.'); window.location.href = '/project_wad/frontend/services/services.php';</script>";
    }
} else {
    echo "<script>alert('Invalid service or quantity.'); window.location.href = '/project_wad/frontend/services/services.php';</script>";
}

$conn->close();
