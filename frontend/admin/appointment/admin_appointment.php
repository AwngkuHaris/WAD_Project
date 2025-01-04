<?php
// Include database connection 
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch services from the database
$services_sql = "SELECT service_id, service_name FROM services";
$services_result = $conn->query($services_sql);

// Fetch appointments from the database
$sql = "SELECT appointment_id, name, phone, service_id, date, time, status FROM appointments";
$result = $conn->query($sql);

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Appointment canceled successfully.'); window.location.href='admin_appointment.php';</script>";
    } else {
        echo "<script>alert('Error canceling appointment.');</script>";
    }
    $stmt->close();
}

$sql = $sql = "
SELECT 
    a.appointment_id, 
    a.name, 
    a.phone, 
    s.service_name, 
    a.problem, 
    a.date, 
    a.time, 
    a.status 
FROM appointments a
LEFT JOIN services s ON a.service_id = s.service_id
ORDER BY a.date, a.time
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Appointments</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_appointment.css">

</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / Manage Appointments</p>
            <h1>Manage Appointments</h1>
        </div>
    </section>

    <div class="main-layout">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/admin/dashboard/admin_dashboard.php">Dashboard</a>
                <a href="/project_wad/frontend/admin/members/member_list.php">Member List</a>
                <a href="/project_wad/frontend/admin/appointment/admin_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/admin/payment/payment_list.php">Payment List</a>
                <a href="/project_wad/frontend/admin/services/services.php">Services</a>
                <a href="/project_wad/frontend/admin/activities/manage_activities.php">Activities</a>
                <a href="/project_wad/frontend/admin/doctors/manage_doctors.php">Doctors</a>
                <a href="/project_wad/frontend/admin/promotions/manage_promotions.php">Promotions</a>
                <a href="logout.php">Log Out</a>
            </nav>
        </aside>

        <section class="manual-appointment-section">
            <h2>Manual Appointment Form</h2>
            <form method="POST" action="/project_wad/backend/admin/process_appointment.php" class="appointment-form">
                <!-- Row 1: Name and Email -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Enter member name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required>
                    </div>
                </div>

                <!-- Row 2: Contact and Appointment Date -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Contact:</label>
                        <input type="text" id="phone" name="phone" placeholder="Enter contact number" required>
                    </div>
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date:</label>
                        <input type="date" id="appointment_date" name="date" required>
                    </div>
                </div>

                <!-- Row 3: Service and Appointment Time -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="service_id">Service:</label>
                        <select id="service_id" name="service_id" required>
                            <option value="">Select Service</option>
                            <?php
                            // Populate services dynamically
                            $services_sql = "SELECT service_id, service_name FROM services";
                            $services_result = $conn->query($services_sql);

                            if ($services_result->num_rows > 0):
                                while ($service = $services_result->fetch_assoc()):
                            ?>
                                    <option value="<?php echo htmlspecialchars($service['service_id']); ?>">
                                        <?php echo htmlspecialchars($service['service_name']); ?>
                                    </option>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <option value="">No services available</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="time">Appointment Time:</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                </div>

                <!-- Row 4: Address -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea id="address" name="address" placeholder="Enter address" required></textarea>
                    </div>
                </div>

                <!-- Row 5: Problem -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="problem">Problem:</label>
                        <textarea id="problem" name="problem" placeholder="Describe the problem" required></textarea>
                    </div>
                </div>

                <!-- Row 6: Status -->
                <div class="form-row full-width">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" required>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="form-row full-width">
                    <button type="submit" name="add_appointment">Add Appointment</button>
                </div>
            </form>
    </div>
    <!-- Appointment List Section -->
    <section class="appointment-list-section">
        <h2> List Of Appointments </h2>
        <table class="appointment-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Service</th>
                    <th>Problem</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['appointment_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['service_name']; ?></td>
                        <td><?php echo $row['problem']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <a href="edit_appointment.php?edit_id=<?php echo $row['appointment_id']; ?>" class="edit-btn">Edit</a>
                            <a href="admin_appointment.php?delete_id=<?php echo $row['appointment_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to cancel this appointment?');">Cancel</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <script src="/project_wad/javascipt/admin_appointment_validation.js"></script>

            </tbody>
        </table>
    </section>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>

</body>

</html>