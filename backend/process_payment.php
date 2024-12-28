<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id']; // Logged-in user ID
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];
    $payment_date = date("Y-m-d"); // Current date
    $receipt_id = uniqid("REC-"); // Generate a unique receipt ID

    // Insert payment into the database
    $stmt = $conn->prepare("
        INSERT INTO payments (appointment_id, user_id, amount, payment_date, receipt_id) 
        VALUES (?, ?, ?, ?, ?)
    ");

    echo "SQL Query: " . $query . "<br>";
    echo "Parameters: appointment_id = $appointment_id, user_id = $user_id, amount = $amount, payment_date = $payment_date, receipt_id = $receipt_id <br>";

    // Corrected: Ensure the types match the variables
    // 'i' for integers (appointment_id, user_id), 'd' for decimal (amount), 's' for strings (payment_date, receipt_id)
    $stmt->bind_param("iidss", $appointment_id, $user_id, $amount, $payment_date, $receipt_id);


    if ($stmt->execute()) {
        header("Location: payment_page.php?success=1"); // Redirect on success
    } else {
        header("Location: payment_page.php?error=1"); // Redirect on error
    }

    $stmt->close();
    $conn->close();
}
