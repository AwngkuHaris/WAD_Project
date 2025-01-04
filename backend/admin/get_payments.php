<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php'; // Use absolute path

header('Content-Type: application/json');

// Get the selected time frame (default to daily)
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'daily';

$data = [];

// Query based on the selected time frame
if ($timeFrame === 'daily') {
    // Fetch payments for the last 7 days, grouped by day
    $query = "
        SELECT DATE(payment_date) as date, SUM(amount) as total
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 7 DAY
        GROUP BY DATE(payment_date)
        ORDER BY DATE(payment_date)
    ";
} elseif ($timeFrame === 'weekly') {
    // Fetch payments for the last 4 weeks, grouped by week (start date of the week)
    $query = "
        SELECT 
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%Y-%m-%d') AS week_start,
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY + INTERVAL 6 DAY, '%Y-%m-%d') AS week_end,
            SUM(amount) as total
        FROM payments
        WHERE payment_date >= CURDATE() - INTERVAL 4 WEEK
        GROUP BY DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%Y-%m-%d')

        ORDER BY week_start
    ";
} elseif ($timeFrame === 'monthly') {
    // Fetch payments for the last 3 months, grouped by month
    $query = "
        SELECT DATE(payment_date) as date, SUM(amount) as total
        FROM payments
        WHERE MONTH(payment_date) = MONTH(CURDATE())
          AND YEAR(payment_date) = YEAR(CURDATE())
        GROUP BY DATE(payment_date)
        ORDER BY DATE(payment_date) ASC

    ";
}

$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo json_encode(['error' => 'Failed to fetch data from the database.']);
    exit();
}

echo json_encode($data);
