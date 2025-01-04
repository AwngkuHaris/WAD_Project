<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (isset($_GET['appointment_id'])) {
    $appointment_id = intval($_GET['appointment_id']);

    // Delete appointment query
    $query = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment deleted successfully.'); window.location.href = '/project_wad/frontend/admin/appointments/upcoming_appointments.php';</script>";
    } else {
        echo "<script>alert('Failed to delete appointment.'); window.location.href = '/project_wad/frontend/admin/appointments/upcoming_appointments.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
