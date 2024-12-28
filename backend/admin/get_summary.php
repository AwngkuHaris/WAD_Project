<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$timeFrame = $_GET['time_frame']; // daily, weekly, or monthly
$data = [];

if ($timeFrame === 'daily') {
    $query = "
        SELECT DATE(payment_date) as date, COUNT(payment_id) as total_transactions, SUM(amount) as total_amount
        FROM payments
        GROUP BY DATE(payment_date)
        ORDER BY DATE(payment_date) DESC
    ";
} elseif ($timeFrame === 'weekly') {
    $query = "
        SELECT CONCAT(DATE_FORMAT(DATE_ADD(payment_date, INTERVAL(1 - DAYOFWEEK(payment_date)) DAY), '%d-%m-%Y'), ' to ', DATE_FORMAT(DATE_ADD(payment_date, INTERVAL(7 - DAYOFWEEK(payment_date)) DAY), '%d-%m-%Y')) AS week,
               COUNT(payment_id) as total_transactions, SUM(amount) as total_amount
        FROM payments
        GROUP BY WEEK(payment_date)
        ORDER BY WEEK(payment_date) DESC
    ";
} elseif ($timeFrame === 'monthly') {
    $query = "
        SELECT DATE_FORMAT(payment_date, '%M %Y') as month, COUNT(payment_id) as total_transactions, SUM(amount) as total_amount
        FROM payments
        GROUP BY MONTH(payment_date), YEAR(payment_date)
        ORDER BY YEAR(payment_date) DESC, MONTH(payment_date) DESC
    ";
}

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($data);
