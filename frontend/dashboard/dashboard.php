<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include '../../backend/db_connect.php'; // Include database connection

// Fetch appointments for the logged-in user
$user_id = $_SESSION['user_id'];

// Fetch future appointments
$stmt_future = $conn->prepare("
    SELECT date, time, status 
    FROM appointments 
    WHERE user_id = ? AND date >= CURDATE()
    ORDER BY date, time
");
$stmt_future->bind_param("i", $user_id);
$stmt_future->execute();
$result_future = $stmt_future->get_result();
$future_appointments = $result_future->fetch_all(MYSQLI_ASSOC);
$stmt_future->close();

// Fetch past appointments
$stmt_past = $conn->prepare("
    SELECT date, time, status 
    FROM appointments 
    WHERE user_id = ? AND date < CURDATE()
    ORDER BY date DESC, time DESC
");
$stmt_past->bind_param("i", $user_id);
$stmt_past->execute();
$result_past = $stmt_past->get_result();
$past_appointments = $result_past->fetch_all(MYSQLI_ASSOC);
$stmt_past->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../styles/dashboard.css"> <!-- Link to the CSS file -->
</head>

<body>
    <?php include '../../header.php'; ?>
    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="#">Dashboard</a>
                <a href="#">Profile</a>
                <a href="#">Services</a>
                <a href="book_appointment.php">Appointment</a>
                <a href="#">Payments</a>
                <a href="#">Cart</a>
                <a href="logout.php">Log Out</a>
            </nav>
        </aside>

        <main class="main-content">
            <section class="top-section">
                <div class="notifications">
                    <h3>Notifications</h3>
                    <div class="notification-item">
                        <div class="circle"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <button class="delete-btn">X</button>
                    </div>
                    <div class="notification-item">
                        <div class="circle"></div>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <button class="delete-btn">X</button>
                    </div>
                </div>

                <div class="upcoming-appointments">
                    <h3>Upcoming Appointments</h3>
                    <?php if (count($future_appointments) > 0): ?>
                        <table>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                            <?php foreach ($future_appointments as $appointment): ?>
                                <tr>
                                <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($appointment['date']))); ?></td>
                                    <td><?php echo htmlspecialchars(date("H:i", strtotime($appointment['time']))); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>No upcoming appointments.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="history-section">
                <div class="treatment-history">
                    <h3>Treatment History</h3>
                    <?php if (count($past_appointments) > 0): ?>
                        <table>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                            <?php foreach ($past_appointments as $appointment): ?>
                                <tr>
                                <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($appointment['date']))); ?></td>
                                    <td><?php echo htmlspecialchars(date("H:i", strtotime($appointment['time']))); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p>No past appointments.</p>
                    <?php endif; ?>
                </div>
            </section>

            <section class="payment-section">
                <div class="payment-list">
                    <h3>Payment List</h3>
                    <table>
                        <tr>
                            <th>Name/Services</th>
                            <th>Date</th>
                            <th>Dentist</th>
                            <th>Deposit</th>
                            <th>Amount</th>
                        </tr>
                        <tr>
                            <td>Bridge</td>
                            <td>10 Jan 2021</td>
                            <td>Lorem</td>
                            <td>Paid</td>
                            <td>Paid</td>
                        </tr>
                    </table>
                </div>
            </section>

        </main>


    </div>
</body>

</html>