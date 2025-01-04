<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/frontend/login_register/memberlogin.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['payment_id'])) {
        $user_id = $_SESSION['user_id'];
        $payment_id = intval($_POST['payment_id']);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Fetch the appointment_id and other details using payment_id
            $stmt_fetch_payment = $conn->prepare("
                SELECT p.appointment_id, p.amount, p.service_name, p.quantity, p.service_price
                FROM payments p
                WHERE p.payment_id = ? AND p.user_id = ?
            ");
            $stmt_fetch_payment->bind_param("ii", $payment_id, $user_id);
            $stmt_fetch_payment->execute();
            $result = $stmt_fetch_payment->get_result();
            $payment = $result->fetch_assoc();
            $stmt_fetch_payment->close();

            if (!$payment) {
                throw new Exception("Invalid payment ID.");
            }

            $appointment_id = $payment['appointment_id'];
            $amount_to_pay = $payment['amount'];
            $service_name = $payment['service_name'];
            $quantity = $payment['quantity'];
            $service_price = $payment['service_price'];

            // Update the payment status to 'completed'
            $stmt_update_payment = $conn->prepare("
                UPDATE payments 
                SET status = 'completed', payment_date = NOW() 
                WHERE payment_id = ? AND user_id = ?
            ");
            $stmt_update_payment->bind_param("ii", $payment_id, $user_id);
            $stmt_update_payment->execute();
            $stmt_update_payment->close();

            // Update the appointment status to 'confirmed'
            $stmt_update_appointment = $conn->prepare("
                UPDATE appointments 
                SET status = 'confirmed' 
                WHERE appointment_id = ? AND user_id = ?
            ");
            $stmt_update_appointment->bind_param("ii", $appointment_id, $user_id);
            $stmt_update_appointment->execute();
            $stmt_update_appointment->close();

            // Generate receipt
            $stmt_receipt = $conn->prepare("
                INSERT INTO receipts (user_id, payment_id, receipt_date, amount, service_name, service_price, quantity)
                VALUES (?, ?, NOW(), ?, ?, ?, ?)
            ");
            $stmt_receipt->bind_param("iissdi", $user_id, $payment_id, $amount_to_pay, $service_name, $service_price, $quantity);
            $stmt_receipt->execute();
            $receipt_id = $conn->insert_id; // Get the receipt ID of the newly created record
            $stmt_receipt->close();

            // Update the `receipt_id` in the payments table
            $stmt_update_payment_receipt = $conn->prepare("
                UPDATE payments 
                SET receipt_id = ? 
                WHERE payment_id = ?
            ");
            $stmt_update_payment_receipt->bind_param("ii", $receipt_id, $payment_id);
            $stmt_update_payment_receipt->execute();
            $stmt_update_payment_receipt->close();

            // Commit the transaction
            $conn->commit();
            echo "<script>alert('Payment completed successfully! Receipt generated.'); window.location.href = '/project_wad/frontend/dashboard/payment_page.php';</script>";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Transaction failed: " . $e->getMessage();
        }
    } else {
        echo "Invalid request: Payment ID is missing.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
