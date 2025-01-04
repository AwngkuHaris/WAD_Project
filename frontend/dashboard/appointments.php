<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/frontend/login_register/memberlogin.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch upcoming appointments
$stmt = $conn->prepare("
    SELECT appointment_id, date, time, status, service_name 
    FROM appointments 
    WHERE user_id = ? AND date >= CURDATE() 
    ORDER BY date ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$upcoming_appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch past appointments
$stmt = $conn->prepare("
    SELECT appointment_id, date, time, status, service_name 
    FROM appointments 
    WHERE user_id = ? AND date < CURDATE() 
    ORDER BY date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$past_appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/appointments.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/user_header.php'; ?>
    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/dashboard/dashboard.php">Dashboard</a>
                <a href="#">Profile</a>
                <a href="/project_wad/frontend/dashboard/user_services.php">Services</a>
                <a href="/project_wad/frontend/dashboard/book_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/dashboard/payment_page.php">Payments</a>
                <a href="/project_wad/frontend/dashboard/user_cart.php">Cart</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>

        <div class="appointments-container">
            <h1>My Appointments</h1>

            <div class="upcoming-appointments">
                <h2>Upcoming Appointments</h2>
                <?php if (!empty($upcoming_appointments)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upcoming_appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($appointment['date']))); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No upcoming appointments.</p>
                <?php endif; ?>
            </div>

            <div class="button-container">
                <!-- Book Appointment Button -->
                <a href="/project_wad/frontend/dashboard/user_services.php" class="book-appointment-btn">
                    Book Appointment
                </a>
            </div>

            <div class="past-appointments">
                <h2>Past Appointments</h2>
                <?php if (!empty($past_appointments)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($past_appointments as $appointment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($appointment['date']))); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['time']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No past appointments.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>