<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];

    // Fetch cart item details and related appointment_id
    // Fetch cart item details and related appointment_id
    $stmt = $conn->prepare("
SELECT c.cart_id, c.quantity, s.price, s.service_name, a.appointment_id 
FROM cart c 
JOIN services s ON c.service_id = s.service_id 
LEFT JOIN appointments a ON c.service_id = a.service_id AND c.user_id = a.user_id
WHERE c.user_id = ? AND c.service_id = ? AND c.status = 'unpaid'
");
    $stmt->bind_param("ii", $user_id, $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_item = $result->fetch_assoc();
    $stmt->close();


    if ($cart_item) {
        $total_amount = $cart_item['quantity'] * $cart_item['price'];
        $appointment_id = $cart_item['appointment_id'];

        // Check if appointment_id exists
        if (!$appointment_id) {
            echo "<script>alert('Appointment not found for this cart item.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
            exit();
        }

        // Add payment record
        $stmt = $conn->prepare("
            INSERT INTO payments (appointment_id, user_id, service_id, amount, payment_date, status) 
            VALUES (?, ?, ?, ?, NOW(), 'completed')
        ");
        $stmt->bind_param("iiid", $appointment_id, $user_id, $service_id, $total_amount);
        $stmt->execute();
        $payment_id = $stmt->insert_id; // Get the inserted payment_id
        $stmt->close();

        // Generate a receipt
        // Generate a receipt
        $stmt = $conn->prepare("
INSERT INTO receipts (user_id, payment_id, receipt_date, amount, details, created_at) 
VALUES (?, ?, NOW(), ?, ?, NOW())
");
        $service_name = $cart_item['service_name']; // Get the service name
        $details = "Payment for service: $service_name";
        $stmt->bind_param("iids", $user_id, $payment_id, $total_amount, $details);
        $stmt->execute();
        $receipt_id = $stmt->insert_id; // Get the inserted receipt_id
        $stmt->close();


        // Update the payments table with the receipt_id
        $stmt = $conn->prepare("
            UPDATE payments SET receipt_id = ? WHERE payment_id = ?
        ");
        $stmt->bind_param("ii", $receipt_id, $payment_id);
        $stmt->execute();
        $stmt->close();

        // Update cart status to 'paid'
        $stmt = $conn->prepare("
            UPDATE cart SET status = 'paid' WHERE cart_id = ?
        ");
        $stmt->bind_param("i", $cart_item['cart_id']);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Payment completed successfully! Receipt generated.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
    } else {
        echo "<script>alert('Invalid cart item or already paid.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
    }
}
$conn->close();
