<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';
// Include database connection

// Fetch appointments for the logged-in user
$user_id = $_SESSION['user_id'];

// Fetch future appointments
$stmt_future = $conn->prepare("
    SELECT date, time, status 
    FROM appointments 
    WHERE user_id = ? AND date >= CURDATE()
    ORDER BY date, time
");
$stmt_future->bind_param("i", $user_id);
$stmt_future->execute();
$result_future = $stmt_future->get_result();
$future_appointments = $result_future->fetch_all(MYSQLI_ASSOC);
$stmt_future->close();

// Fetch past appointments
$stmt_past = $conn->prepare("
    SELECT date, time, status 
    FROM appointments 
    WHERE user_id = ? AND date < CURDATE()
    ORDER BY date DESC, time DESC
");
$stmt_past->bind_param("i", $user_id);
$stmt_past->execute();
$result_past = $stmt_past->get_result();
$past_appointments = $result_past->fetch_all(MYSQLI_ASSOC);
$stmt_past->close();

// Fetch payment list
$stmt_payments = $conn->prepare("
    SELECT 
        p.payment_date AS payment_date,
        p.amount AS amount,
        p.receipt_id AS receipt_id
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    WHERE p.user_id = ?
    ORDER BY p.payment_date DESC
");
$stmt_payments->bind_param("i", $user_id);
$stmt_payments->execute();
$result_payments = $stmt_payments->get_result();
$payments = $result_payments->fetch_all(MYSQLI_ASSOC);
$stmt_payments->close();


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            text-align: center;
        }

        canvas {
            max-width: 800px;
            height: 400px;
            margin: 20px auto;
        }
    </style>

</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <h1>Payments Chart</h1>
    <label for="timeFrame">Select Time Frame:</label>
    <select id="timeFrame">
        <option value="daily">Daily</option>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
    </select>

    <canvas id="paymentsChart"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('paymentsChart').getContext('2d');
        let paymentsChart;

        // Function to fetch data and render the chart
        async function fetchAndRenderChart(timeFrame = 'daily') {
            try {
                const response = await fetch(`http://localhost/project_wad/backend/admin/get_payments.php?time_frame=${timeFrame}`);


                const data = await response.json();

                // Extract labels and values
                const labels = data.map(item => item.date || item.week || item.month);
                const totals = data.map(item => item.total);

                // Destroy the chart if it already exists
                if (paymentsChart) {
                    paymentsChart.destroy();
                }

                // Create the chart
                paymentsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Payments (${timeFrame})`,
                            data: totals,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Initial chart load
        fetchAndRenderChart();

        // Update chart when dropdown changes
        document.getElementById('timeFrame').addEventListener('change', (event) => {
            fetchAndRenderChart(event.target.value);
        });
    </script>

</body>

</html>