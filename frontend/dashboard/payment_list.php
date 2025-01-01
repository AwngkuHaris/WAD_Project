<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch user_id from session
$user_id = $_SESSION['user_id'];

// Fetch past payments for the user
$query = "
    SELECT 
        p.payment_id, 
        p.amount, 
        p.payment_date, 
        p.status, 
        r.receipt_id 
    FROM payments p
    LEFT JOIN receipts r ON p.payment_id = r.payment_id
    WHERE p.user_id = ?
    ORDER BY p.payment_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$payments = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
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

        <div class="payment-container">
            <h1>Payment History</h1>
            <table class="payment-table">
                <thead>
                    <tr>
                        <th>Receipt ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['receipt_id']); ?></td>
                                <td>RM<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($payment['payment_date']))); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($payment['status'])); ?></td>
                                <td>
                                    <?php if ($payment['receipt_id']): ?>
                                        <a href="/project_wad/frontend/dashboard/view_receipt.php?receipt_id=<?php echo $payment['receipt_id']; ?>" class="receipt-btn">View Receipt</a>
                                    <?php else: ?>
                                        <span>No Receipt</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No payments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>