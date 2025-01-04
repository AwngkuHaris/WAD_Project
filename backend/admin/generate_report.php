<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Get the selected time frame
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'daily';

// Set headers to force download as an Excel file
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="transaction_summary_report.xls"');

// Start the output buffer
ob_start();
?>

<table border="1">
    <tr>
        <th>Date</th>
        <th>Total Transactions</th>
        <th>Total Amount (RM)</th>
    </tr>

<?php
if ($timeFrame === 'daily') {
    $query = "
        SELECT DATE(payment_date) AS date, COUNT(*) AS total_transactions, SUM(amount) AS total_amount
        FROM payments
        WHERE DATE(payment_date) = CURDATE()
        GROUP BY DATE(payment_date)
    ";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . date('d/m/Y', strtotime($row['date'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_transactions']) . "</td>";
        echo "<td>RM" . number_format($row['total_amount'], 2) . "</td>";
        echo "</tr>";
    }
} elseif ($timeFrame === 'weekly') {
    $query = "
        SELECT 
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%d/%m/%Y') AS week_start,
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY + INTERVAL 6 DAY, '%d/%m/%Y') AS week_end,
            COUNT(*) AS total_transactions,
            SUM(amount) AS total_amount
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 4 WEEK
        GROUP BY week_start
    ";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . "{$row['week_start']} - {$row['week_end']}" . "</td>";
        echo "<td>" . htmlspecialchars($row['total_transactions']) . "</td>";
        echo "<td>RM" . number_format($row['total_amount'], 2) . "</td>";
        echo "</tr>";
    }
} elseif ($timeFrame === 'monthly') {
    $query = "
        SELECT DATE_FORMAT(payment_date, '%m/%Y') AS month, COUNT(*) AS total_transactions, SUM(amount) AS total_amount
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 3 MONTH
        GROUP BY YEAR(payment_date), MONTH(payment_date)
    ";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['month'] . "</td>";
        echo "<td>" . htmlspecialchars($row['total_transactions']) . "</td>";
        echo "<td>RM" . number_format($row['total_amount'], 2) . "</td>";
        echo "</tr>";
    }
}
?>
</table>

<?php
// Flush the output buffer
ob_end_flush();
$conn->close();
