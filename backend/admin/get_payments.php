<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php'; // Use absolute path

header('Content-Type: application/json');

// Get the selected time frame (default to daily)
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'daily';

$data = [];

// Query based on the selected time frame
if ($timeFrame === 'daily') {
    $query = "
        SELECT DATE(payment_date) as date, SUM(amount) as total
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 7 DAY
        GROUP BY DATE(payment_date)
        ORDER BY DATE(payment_date)
    ";
} elseif ($timeFrame === 'weekly') {
    $query = "
        SELECT 
    DATE(payment_date) AS date, 
    SUM(amount) AS total
FROM payments
WHERE payment_date >= CURDATE() - INTERVAL 1 MONTH
GROUP BY DATE(payment_date)
ORDER BY DATE(payment_date)
    ";
} elseif ($timeFrame === 'monthly') {
    $query = "
        SELECT MONTHNAME(payment_date) as month, SUM(amount) as total
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 1 YEAR
        GROUP BY MONTH(payment_date)
        ORDER BY MONTH(payment_date)
    ";
}

$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
