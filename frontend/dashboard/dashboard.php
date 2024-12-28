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

// Fetch payment list
$stmt_payments = $conn->prepare("
    SELECT 
        p.payment_date AS payment_date,
        p.amount AS amount,
        p.receipt_id AS receipt_id
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    WHERE p.user_id = ?
    ORDER BY p.payment_date DESC
");
$stmt_payments->bind_param("i", $user_id);
$stmt_payments->execute();
$result_payments = $stmt_payments->get_result();
$payments = $result_payments->fetch_all(MYSQLI_ASSOC);
$stmt_payments->close();


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
                <a href="payment_page.php">Payments</a>
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
                    <?php if (count($payments) > 0): ?>
                        <table>
                            <thead>
                                <tr>

                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Receipt ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>

                                        <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($payment['payment_date']))); ?></td>
                                        <td><?php echo 'RM ' . htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
                                        <td><?php echo htmlspecialchars($payment['receipt_id']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No payments found.</p>
                    <?php endif; ?>
                </div>
            </section>

        </main>


    </div>
</body>

</html>