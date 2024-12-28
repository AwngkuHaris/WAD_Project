<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include 'db_connect.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service_id = $_POST['service_id'];
    $status = "Pending"; // Default status for new appointments
    $created_at = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO appointments (user_id, date, time, service_id, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $user_id, $date, $time, $service_id, $status, $created_at);

    if ($stmt->execute()) {
        header("Location: book_appointment.php?success=1"); // Redirect with success message
    } else {
        header("Location: book_appointment.php?error=1"); // Redirect with error message
    }

    $stmt->close();
    $conn->close();
}
?>
