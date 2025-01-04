<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

// Fetch receipt details using receipt_id
$query = "
    SELECT 
        r.receipt_id, 
        r.receipt_date, 
        r.amount, 
        r.service_name, 
        r.service_price, 
        r.quantity 
    FROM receipts r
    WHERE r.receipt_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();
$stmt->close();

if (!$receipt) {
    echo "<script>console.log('Receipt not found for payment_id: {$payment_id}');</script>";

    exit();
}

// Fetch user details
$query_user = "
    SELECT u.fullName, u.address, u.postcode, u.city, u.country 
    FROM users u
    JOIN payments p ON u.user_id = p.user_id
    JOIN receipts r ON r.payment_id = p.payment_id
    WHERE r.receipt_id = ?
";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("i", $receipt_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

if (!$user) {
    echo "<script>console.log('User details not found for payment_id: {$payment_id}');</script>";
}

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
    <script>
        // Debugging variables in the browser console
        console.log("Debug Info:");
        console.log("Payment ID: <?php echo $payment_id; ?>");
        console.log("Receipt: <?php echo json_encode($receipt); ?>");
        console.log("User: <?php echo json_encode($user); ?>");
    </script>

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
