<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$payment_id = isset($_GET['payment_id']) ? intval($_GET['payment_id']) : 0;

// Fetch receipt details using payment_id
$query = "
    SELECT 
        r.receipt_id, 
        r.receipt_date, 
        r.amount, 
        r.service_name, 
        r.service_price, 
        r.quantity 
    FROM receipts r
    WHERE r.payment_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);

$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();
$stmt->close();

if (!$receipt) {
    echo "<script>alert('Receipt not found.'); window.location.href = '/project_wad/frontend/dashboard/payment_page.php';</script>";
    exit();
}

// Fetch user details
$stmt_user = $conn->prepare("
    SELECT fullName, address, postcode, city, country 
    FROM users 
    WHERE user_id = ?
");
$stmt_user->bind_param("i", $_SESSION['user_id']);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

$conn->close();

// Calculate SUB TOTAL and TOTAL with 8% tax
$sub_total = $receipt['service_price'] * $receipt['quantity'];
$tax = $sub_total * 0.08; // 8% tax
$total = $sub_total + $tax;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/receipt.css">
</head>

<body>
    <div class="receipt-container">
        <div class="header">
            <h2>RECEIPT</h2>
            <img src="/project_wad/images/tooth_logo.png" alt="Logo">
        </div>
        <div class="clinic-address">
            <strong>Klinik Pakar Pergigian Anmas</strong>
            <p>
                31, Lorong Uni Garden 1,<br>
                94300 Kota Samarahan,<br>
                Sarawak
            </p>
        </div>

        <div class="header-info">
            <div class="bill-to">
                <strong>Bill To:</strong>
                <p><?php echo htmlspecialchars($user['fullName']); ?></p>
                <p><?php echo htmlspecialchars($user['address']); ?></p>
                <p><?php echo htmlspecialchars($user['postcode'] . ', ' . $user['city']); ?></p>
                <p><?php echo htmlspecialchars($user['country']); ?></p>
            </div>

            <div class="receipt-details">
                <strong>Receipt No:</strong>
                <p><?php echo 'AS - ' . str_pad($receipt['receipt_id'], 3, '0', STR_PAD_LEFT); ?></p>
                <br>
                <strong>Receipt Date:</strong>
                <p><?php echo htmlspecialchars(date('d/m/Y', strtotime($receipt['receipt_date']))); ?></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>PRICE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($receipt['service_name']); ?></td>
                    <td><?php echo htmlspecialchars($receipt['quantity']); ?></td>
                    <td>RM<?php echo number_format($receipt['service_price'], 2); ?></td>
                </tr>
            </tbody>
        </table>
        <div class="totals">
            <strong>SUB TOTAL:</strong> RM<?php echo number_format($sub_total, 2); ?><br>
            <strong>SST (8%):</strong> RM<?php echo number_format($tax, 2); ?><br>
            <strong>TOTAL:</strong> RM<?php echo number_format($total, 2); ?>
        </div>
    </div>
</body>

</html>
