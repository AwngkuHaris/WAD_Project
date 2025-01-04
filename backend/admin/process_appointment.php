<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $problem = trim($_POST['problem']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);
    $service_id = trim($_POST['service_id']);
    $status = 'pending';

    // Validate inputs
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $errors[] = "Phone number must be between 10 and 15 digits.";
    }

    if (empty($date)) {
        $errors[] = "Appointment date is required.";
    }

    if (empty($time)) {
        $errors[] = "Appointment time is required.";
    }

    if (empty($service_id)) {
        $errors[] = "Service is required.";
    }

    // If validation fails, redirect back with errors
    if (!empty($errors)) {
        echo "<script>alert('".implode("\\n", $errors)."'); window.history.back();</script>";
        exit();
    }

    // Proceed to save the data
    $sql = "INSERT INTO appointments (name, email, phone, address, problem, date, time, service_id, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $email, $phone, $address, $problem, $date, $time, $service_id, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment added successfully.'); window.location.href='/project_wad/frontend/admin/appointment/admin_appointment.php';</script>";
    } else {
        echo "<script>alert('Error adding appointment.');</script>";
    }
    $stmt->close();
}
?>
