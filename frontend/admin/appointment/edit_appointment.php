<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch appointment details based on the ID
if (!isset($_GET['edit_id'])) {
    header("Location: admin_appointment.php?error=NoAppointmentSelected");
    exit();
}

$edit_id = $_GET['edit_id'];
$sql = "SELECT * FROM appointments WHERE appointment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $edit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_appointment.php?error=AppointmentNotFound");
    exit();
}
$edit_appointment = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_appointment.css">
</head>
<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="admin_appointment.php">Manage Appointments</a> / Edit Appointments</p>
            <h1>Edit Appointment</h1>
        </div>
    </section>

    <div class="container">
        <h2>Edit Appointment</h2>
        <form method="POST" action="/project_wad/backend/admin/process_edit_appointment.php" class="appointment-form">
            <input type="hidden" name="appointment_id" value="<?php echo $edit_appointment['appointment_id']; ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_appointment['name']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($edit_appointment['email']); ?>" required>

            <label for="phone">Contact:</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($edit_appointment['phone']); ?>" required>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required><?php echo htmlspecialchars($edit_appointment['address']); ?></textarea>

            <label for="problem">Problem:</label>
            <textarea id="problem" name="problem" required><?php echo htmlspecialchars($edit_appointment['problem']); ?></textarea>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" value="<?php echo $edit_appointment['date']; ?>" required>

            <label for="time">Appointment Time:</label>
            <input type="time" id="time" name="time" value="<?php echo $edit_appointment['time']; ?>" required>

            <label for="service_id">Service:</label>
            <select id="service_id" name="service_id" required>
                <option value="">Select Service</option>
                <?php
                $services_sql = "SELECT service_id, service_name FROM services";
                $services_result = $conn->query($services_sql);

                if (!$services_result) {
                    die("Error fetching services: " . $conn->error);
                }

                while ($service = $services_result->fetch_assoc()):
                ?>
                    <option value="<?php echo $service['service_id']; ?>" <?php echo $edit_appointment['service_id'] == $service['service_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($service['service_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="pending" <?php echo $edit_appointment['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="approved" <?php echo $edit_appointment['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                <option value="rejected" <?php echo $edit_appointment['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
            </select>

            <button type="submit" name="update_appointment">Update Appointment</button>
        </form>

        <script src="/project_wad/javascipt/admin_appointment_validation.js"></script>
        
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>

</body>
</html>
