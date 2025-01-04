<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/frontend/login_register/memberlogin.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch pending payments (payments with 'pending' status)
$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.service_name,
        p.amount,
        p.quantity,
        p.service_price,
        a.date AS appointment_date,
        a.time AS appointment_time
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    WHERE p.user_id = ? AND p.status = 'pending'
    ORDER BY a.date ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$pending_payments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch completed payments (payments with 'completed' status)
$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.service_name,
        p.amount,
        p.quantity,
        p.service_price,
        a.date AS appointment_date,
        a.time AS appointment_time,
        p.payment_date
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    WHERE p.user_id = ? AND p.status = 'completed'
    ORDER BY p.payment_date DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$completed_payments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Payments</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/payment_list.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>
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

        <div class="payments-container">
            <h1>My Payments</h1>

            <!-- Pending Payments Section -->
            <div class="pending-payments">
                <h2>Pending Payments</h2>
                <?php if (!empty($pending_payments)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($payment['appointment_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($payment['appointment_time']); ?></td>
                                    <td>RM<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td>
                                        <form action="/project_wad/backend/process_payment.php" method="POST">
                                            <input type="hidden" name="payment_id" value="<?php echo htmlspecialchars($payment['payment_id']); ?>">
                                            <button type="submit" class="pay-btn">Pay Now</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No pending payments found.</p>
                <?php endif; ?>
            </div>

            <!-- Completed Payments Section -->
            <div class="completed-payments">
                <h2>Completed Payments</h2>
                <?php if (!empty($completed_payments)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Service Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Payment Date</th>
                                <th>Amount</th>
                                <th>Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completed_payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($payment['appointment_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($payment['appointment_time']); ?></td>
                                    <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($payment['payment_date']))); ?></td>
                                    <td>RM<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td>
                                        <form action="/project_wad/frontend/dashboard/view_receipt.php" method="GET">
                                            <input type="hidden" name="payment_id" value="<?php echo $payment['payment_id']; ?>">
                                            <button type="submit" class="view-receipt-btn">View Receipt</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No completed payments found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>