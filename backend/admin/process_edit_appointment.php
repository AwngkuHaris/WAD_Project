<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $problem = $_POST['problem'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service_id = $_POST['service_id'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments SET 
            name = ?, 
            email = ?, 
            phone = ?, 
            address = ?, 
            problem = ?, 
            date = ?, 
            time = ?, 
            service_id = ?, 
            status = ? 
            WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssi",
        $name,
        $email,
        $phone,
        $address,
        $problem,
        $date,
        $time,
        $service_id,
        $status,
        $appointment_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Appointment updated successfully.'); window.location.href='/project_wad/frontend/admin/appointment/admin_appointment.php';</script>";
    } else {
        echo "<script>alert('Error updating appointment.');</script>";
    }
    $stmt->close();
}
?>
