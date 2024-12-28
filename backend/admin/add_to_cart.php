<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

$service_id = $data['service_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$service_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid service ID']);
    exit();
}

// Check if the item is already in the cart
$query = "SELECT * FROM cart WHERE user_id = ? AND service_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $user_id, $service_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity if item already exists
    $query = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND service_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $quantity, $user_id, $service_id);
} else {
    // Insert new item into the cart
    $query = "INSERT INTO cart (user_id, service_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iii', $user_id, $service_id, $quantity);
}

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Service added to cart']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add service to cart']);
}

$stmt->close();
$conn->close();
?>
