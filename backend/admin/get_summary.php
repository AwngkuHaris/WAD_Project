<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

header('Content-Type: application/json');

$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'daily';
$data = [];

if ($timeFrame === 'daily') {
    $query = "
        SELECT DATE(payment_date) as date, COUNT(*) as total_transactions, SUM(amount) as total_amount
        FROM payments
        WHERE DATE(payment_date) = CURDATE()
        GROUP BY DATE(payment_date)
        ORDER BY DATE(payment_date)
    ";

    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'date' => date('d/m/Y', strtotime($row['date'])), // Format as dd/mm/yyyy
                'total_transactions' => (int)$row['total_transactions'],
                'total_amount' => number_format($row['total_amount'], 2),
            ];
        }
    }
} elseif ($timeFrame === 'weekly') {
    $query = "
        SELECT 
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%Y-%m-%d') AS week_start,
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY + INTERVAL 6 DAY, '%Y-%m-%d') AS week_end,
            COUNT(*) as total_transactions, 
            SUM(amount) as total_amount
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 4 WEEK
        GROUP BY week_start
        ORDER BY week_start
    ";

    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'date' => date('d/m/Y', strtotime($row['week_start'])) . ' - ' . date('d/m/Y', strtotime($row['week_end'])), // Format as dd/mm/yyyy - dd/mm/yyyy
                'total_transactions' => (int)$row['total_transactions'],
                'total_amount' => number_format($row['total_amount'], 2),
            ];
        }
    }
} elseif ($timeFrame === 'monthly') {
    $query = "
        SELECT 
            DATE_FORMAT(payment_date, '%M %Y') as month, 
            COUNT(*) as total_transactions, 
            SUM(amount) as total_amount
        FROM payments
        WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY YEAR(payment_date), MONTH(payment_date)
        ORDER BY YEAR(payment_date), MONTH(payment_date)
    ";

    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'date' => $row['month'], // Month Year format (e.g., January 2025)
                'total_transactions' => (int)$row['total_transactions'],
                'total_amount' => number_format($row['total_amount'], 2),
            ];
        }
    }
}

echo json_encode($data);
?>
