<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$receipt_id = isset($_GET['receipt_id']) ? intval($_GET['receipt_id']) : 0;

// Fetch receipt details
$query = "
    SELECT r.receipt_id, r.receipt_date, r.amount, r.details, r.created_at 
    FROM receipts r
    WHERE r.receipt_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();
$receipt = $result->fetch_assoc();

$stmt->close();
$conn->close();

if (!$receipt) {
    echo "Receipt not found.";
    exit();
}
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
        <h1>Receipt</h1>
        <p><strong>Receipt ID:</strong> <?php echo htmlspecialchars($receipt['receipt_id']); ?></p>
        <p><strong>Date:</strong> <?php echo htmlspecialchars(date('d-m-Y', strtotime($receipt['receipt_date']))); ?></p>
        <p><strong>Amount:</strong> RM<?php echo number_format($receipt['amount'], 2); ?></p>
        <p><strong>Details:</strong> <?php echo htmlspecialchars($receipt['details']); ?></p>
        <p><strong>Created At:</strong> <?php echo htmlspecialchars($receipt['created_at']); ?></p>
    </div>
</body>
</html>
