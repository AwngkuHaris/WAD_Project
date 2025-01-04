<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php'; // Use absolute path

header('Content-Type: application/json');

// Get the selected time frame (default to daily)
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'daily';

$data = [];

if ($timeFrame === 'daily') {
    // Fetch payments for the last 7 days, including today
    $query = "
        SELECT DATE(payment_date) as date, SUM(amount) as total
    FROM payments
    WHERE DATE(payment_date) BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
    GROUP BY DATE(payment_date)
    ORDER BY DATE(payment_date)
    ";

    $result = $conn->query($query);

    // Create an array for the past 7 days
    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
        $dates[date('Y-m-d', strtotime("-$i day"))] = 0; // Initialize all dates to 0
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $dates[$row['date']] = (float)$row['total']; // Replace totals for dates with data
        }
    }

    // Prepare data in a structured format
    foreach ($dates as $date => $total) {
        $data[] = ['date' => $date, 'total' => $total];
    }
} elseif ($timeFrame === 'weekly') {
    $startDate = date('Y-m-d', strtotime('-4 weeks'));
    $query = "
        SELECT 
            DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%Y-%m-%d') AS week_start,
            SUM(amount) AS total
        FROM payments
        WHERE payment_date >= '$startDate'
        GROUP BY DATE_FORMAT(payment_date - INTERVAL WEEKDAY(payment_date) DAY, '%Y-%m-%d')
        ORDER BY week_start
    ";

    $result = $conn->query($query);

    $weekData = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $weekData[$row['week_start']] = $row['total'];
        }
    }

    $currentWeek = new DateTime('last monday');
    for ($i = 0; $i < 4; $i++) {
        $weekStart = $currentWeek->format('Y-m-d');
        $weekEnd = $currentWeek->modify('+6 days')->format('Y-m-d');
        $label = "$weekStart - $weekEnd";
        $data[] = ['week_range' => $label, 'total' => $weekData[$weekStart] ?? 0];
        $currentWeek->modify('-13 days'); // Move back 7 days to start a new range
    }
} elseif ($timeFrame === 'monthly') {
    $query = "
        SELECT DATE_FORMAT(payment_date, '%Y-%m') AS month, SUM(amount) AS total
        FROM payments
        WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
        GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
        ORDER BY month
    ";

    $result = $conn->query($query);

    $monthData = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $monthData[$row['month']] = $row['total'];
        }
    }

    for ($i = 2; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i months"));
        $data[] = ['month' => $month, 'total' => $monthData[$month] ?? 0];
    }
}

echo json_encode($data);
