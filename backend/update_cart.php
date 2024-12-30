<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Parse JSON payload
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
$service_id = isset($data['service_id']) ? intval($data['service_id']) : 0;
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 0;

if ($service_id > 0 && $quantity > 0) {
    session_start();
    $user_id = $_SESSION['user_id'];

    // Update the quantity in the database
    $query = "UPDATE cart SET quantity = ? WHERE service_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $quantity, $service_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error."]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}
?>
