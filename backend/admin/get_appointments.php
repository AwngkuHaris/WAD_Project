<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php'; // Use absolute path

header('Content-Type: application/json');

// Get the selected time frame (default to daily)
$timeFrame = isset($_GET['time_frame']) ? $_GET['time_frame'] : 'weekly';

$data = [];

if ($timeFrame === 'daily') {
    // Fetch appointments for the last 7 days, excluding future dates
    $query = "
        SELECT DATE(date) as date, COUNT(*) as total
        FROM appointments
        WHERE date BETWEEN CURDATE() - INTERVAL 6 DAY AND CURDATE()
        GROUP BY DATE(date)
        ORDER BY DATE(date)
    ";

    $result = $conn->query($query);

    // Create an array for the past 7 days
    $dates = [];
    for ($i = 6; $i >= 0; $i--) {
        $dates[date('d/m/Y', strtotime("-$i day"))] = 0; // Initialize all dates to 0
    }

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $formattedDate = date('d/m/Y', strtotime($row['date'])); // Format date as dd/mm/yyyy
            $dates[$formattedDate] = (int)$row['total']; // Replace totals for dates with data
        }
    }

    // Prepare data in a structured format
    foreach ($dates as $date => $total) {
        $data[] = ['date' => $date, 'total' => $total];
    }
} elseif ($timeFrame === 'weekly') {
    // Step 1: Pre-fill the last 4 weeks with zero appointments
    $weeks = [];
    $currentMonday = strtotime("last Monday", strtotime("tomorrow")); // Start of the current week
    for ($i = 0; $i < 4; $i++) {
        $weekStart = date('d/m/Y', strtotime("-$i week", $currentMonday)); // Start of the week
        $weekEnd = date('d/m/Y', strtotime("+6 days", strtotime("-$i week", $currentMonday))); // End of the week
        $weeks["$weekStart - $weekEnd"] = 0; // Initialize all weeks to 0
    }



    // Step 2: Fetch data for weeks with appointments from the database
    $query = "
        SELECT 
            DATE_FORMAT(date - INTERVAL WEEKDAY(date) DAY, '%d/%m/%Y') AS week_start,
            DATE_FORMAT(date - INTERVAL WEEKDAY(date) DAY + INTERVAL 6 DAY, '%d/%m/%Y') AS week_end,
            COUNT(*) as total
        FROM appointments
        WHERE date BETWEEN CURDATE() - INTERVAL 4 WEEK AND CURDATE()
        GROUP BY week_start
        ORDER BY week_start
    ";



    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $weekRange = "{$row['week_start']} - {$row['week_end']}"; // Combine week_start and week_end

            if (isset($weeks[$weekRange])) {
                $weeks[$weekRange] = (int)$row['total']; // Update totals for weeks with data
            }
        }
    }



    // Step 3: Format the data for the frontend
    foreach ($weeks as $weekRange => $total) {
        $data[] = ['week_range' => $weekRange, 'total' => $total];
    }
    echo json_encode($data);
    exit;
} elseif ($timeFrame === 'monthly') {
    $query = "
         SELECT DATE_FORMAT(date, '%M %Y') as month, COUNT(*) as total
        FROM appointments
        WHERE date >= CURDATE() - INTERVAL 3 MONTH
          AND date <= LAST_DAY(CURDATE()) -- Ensure dates are up to the current month
        GROUP BY YEAR(date), MONTH(date)
        ORDER BY YEAR(date) ASC, MONTH(date) ASC
    ";

    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}

echo json_encode($data);
