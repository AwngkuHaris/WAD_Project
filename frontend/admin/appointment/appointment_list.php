<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch all upcoming appointments (date >= today's date)
$query = "
    SELECT 
        a.appointment_id,
        a.user_id,
        a.user_identifier,
        a.date,
        a.time,
        a.quantity,
        a.status,
        s.service_name
    FROM appointments a
    LEFT JOIN services s ON a.service_id = s.service_id
    WHERE a.date >= CURDATE()
    ORDER BY a.date ASC, a.time ASC
";


$result = $conn->query($query);

// Fetch appointments
$appointments = [];
if ($result && $result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/appointment_list.css">
</head>

<body>
    <div class="container">
        <h1>All Upcoming Appointments</h1>
        <?php if (!empty($appointments)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>User ID</th>
                        <th>User Identifier</th>
                        <th>Service Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?php echo htmlspecialchars($appointment['appointment_id']); ?></td>
                <td><?php echo htmlspecialchars($appointment['user_id']); ?></td>
                <td><?php echo htmlspecialchars($appointment['user_identifier'] ?: '-'); ?></td>
                <td><?php echo htmlspecialchars($appointment['service_name'] ?: 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($appointment['date']); ?></td>
                <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                <td><?php echo htmlspecialchars($appointment['quantity']); ?></td>
                <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                <td>
                    <!-- Reschedule Button -->
                    <button
                        class="reschedule-btn"
                        onclick="openRescheduleForm('<?php echo $appointment['appointment_id']; ?>')">
                        Reschedule
                    </button>
                    
                    <!-- Delete Button -->
                    <button
                        class="delete-btn"
                        onclick="deleteAppointment('<?php echo $appointment['appointment_id']; ?>')">
                        Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="9">No upcoming appointments found.</td>
        </tr>
    <?php endif; ?>
</tbody>


            </table>
        <?php else: ?>
            <p>No upcoming appointments found.</p>
        <?php endif; ?>
    </div>

    <script>
    // Delete Appointment
    function deleteAppointment(appointmentId) {
        if (confirm('Are you sure you want to delete this appointment?')) {
            window.location.href = `/project_wad/backend/admin/delete_appointment.php?appointment_id=${appointmentId}`;
        }
    }

    // Open Reschedule Form
    function openRescheduleForm(appointmentId) {
        // Redirect to reschedule page with the appointment ID
        window.location.href = `/project_wad/frontend/admin/appointment/reschedule_appointment.php?appointment_id=${appointmentId}`;
    }
</script>

</body>

</html>