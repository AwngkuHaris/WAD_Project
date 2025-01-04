<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (isset($_GET['appointment_id'])) {
    $appointment_id = intval($_GET['appointment_id']);

    // Fetch appointment details
    $query = "SELECT * FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    $stmt->close();
}

// Reschedule Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_date = $_POST['new_date'];
    $new_time = $_POST['new_time'];

    $update_query = "UPDATE appointments SET date = ?, time = ? WHERE appointment_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment rescheduled successfully.'); window.location.href = '/project_wad/frontend/admin/appointment/appointment_list.php';</script>";
    } else {
        echo "<script>alert('Failed to reschedule appointment.'); window.location.href = '/project_wad/frontend/admin/appointment/appointment_list.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reschedule Appointment</title>
</head>
<body>
    <h1>Reschedule Appointment</h1>
    <form method="POST">
        <label for="new_date">New Date:</label>
        <input type="date" id="new_date" name="new_date" value="<?php echo htmlspecialchars($appointment['date']); ?>" required>
        <br>
        <label for="new_time">New Time:</label>
        <input type="time" id="new_time" name="new_time" value="<?php echo htmlspecialchars($appointment['time']); ?>" required>
        <br>
        <button type="submit">Save Changes</button>
        <button type="button" onclick="window.location.href='/project_wad/frontend/admin/appointments/upcoming_appointments.php'">Cancel</button>
    </form>
</body>
</html>
