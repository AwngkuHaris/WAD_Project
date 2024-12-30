<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';
// Include database connection

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_dashboard.css">
</head>

<body>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/admin/dashboard/admin_dashboard.php">Dashboard</a>
                <a href="/project_wad/frontend/admin/members/member_list.php">Member List</a>
                <a href="#">Appointment</a>
                <a href="/project_wad/frontend/admin/payment/payment_list.php">Payment List</a>
                <a href="/project_wad/frontend/admin/services/services.php">Services</a>
                <a href="/project_wad/frontend/admin/activities/manage_activities.php">Activities</a>
                <a href="/project_wad/frontend/admin/doctors/manage_doctors.php">Doctors</a>
                <a href="/project_wad/frontend/admin/promotions/manage_promotions.php">Promotions</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>
    </div>

    <h1>Payments Chart</h1>
    <label for="chartType">Select Chart Type:</label>
    <select id="chartType">
        <option value="bar">Bar Chart</option>
        <option value="line">Line Chart</option>
    </select>
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

        // Function to group days into weeks
        function groupDaysIntoWeeks(data) {
            const grouped = {};

            data.forEach(item => {
                const date = new Date(item.date);
                const weekStart = new Date(date);
                weekStart.setDate(date.getDate() - date.getDay()); // Get Sunday as the start of the week
                const weekKey = weekStart.toISOString().split('T')[0]; // Use week start date as key

                if (!grouped[weekKey]) {
                    grouped[weekKey] = [];
                }
                grouped[weekKey].push(item);
            });

            return grouped;
        }

        // Function to fetch data and render the chart
        async function fetchAndRenderChart(timeFrame = 'daily', chartType = 'bar') {
            try {
                const response = await fetch(`http://localhost/project_wad/backend/admin/get_payments.php?time_frame=${timeFrame}`);
                const data = await response.json();

                let labels = [];
                let totals = [];

                if (timeFrame === 'weekly') {
                    const groupedWeeks = groupDaysIntoWeeks(data);

                    for (const weekStart in groupedWeeks) {
                        const days = groupedWeeks[weekStart];
                        days.forEach(day => {
                            labels.push(day.date); // Add every day of the week to the labels
                            totals.push(day.total);
                        });
                    }
                } else {
                    // For daily/monthly, use the data directly
                    labels = data.map(item => item.date || item.month);
                    totals = data.map(item => item.total);
                }

                // Destroy the chart if it already exists
                if (paymentsChart) {
                    paymentsChart.destroy();
                }

                // Create the chart
                paymentsChart = new Chart(ctx, {
                    type: chartType, // Dynamic chart type
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Payments (${timeFrame})`, // Add "RM" directly to the label
                            data: totals,
                            backgroundColor: chartType === 'bar' ? 'rgba(75, 192, 192, 0.2)' : 'rgba(75, 192, 192, 0.1)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                            fill: chartType === 'line' // Enable fill for line chart
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
                console.error('Error fetching or rendering chart:', error);
            }
        }

        // Initial chart load
        fetchAndRenderChart();

        // Event listeners for time frame and chart type selection
        document.getElementById('timeFrame').addEventListener('change', (event) => {
            const timeFrame = event.target.value;
            const chartType = document.getElementById('chartType').value; // Get selected chart type
            fetchAndRenderChart(timeFrame, chartType);
        });

        document.getElementById('chartType').addEventListener('change', (event) => {
            const chartType = event.target.value;
            const timeFrame = document.getElementById('timeFrame').value; // Get selected time frame
            fetchAndRenderChart(timeFrame, chartType);
        });
    </script>

</body>

</html>