<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch today's appointments from the database
$query = "SELECT name, service_name, time FROM appointments WHERE date = CURDATE()";
$result = $conn->query($query);

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

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>

    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/admin/dashboard/admin_dashboard.php">Dashboard</a>
                <a href="/project_wad/frontend/admin/members/member_list.php">Member List</a>
                <a href="/project_wad/frontend/admin/appointment/admin_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/admin/payment/payment_list.php">Payment List</a>
                <a href="/project_wad/frontend/admin/services/services.php">Services</a>
                <a href="/project_wad/frontend/admin/activities/manage_activities.php">Activities</a>
                <a href="/project_wad/frontend/admin/doctors/manage_doctors.php">Doctors</a>
                <a href="/project_wad/frontend/admin/promotions/manage_promotions.php">Promotions</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>
        <main class="content">

            <div class="charts-container">
                <section class="appointment-list-section">
                    <h2>Today's Appointments</h2>
                    <table class="appointment-list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Service</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
                <div class="chart">
                    <h1>Appointments Chart</h1>
                    <label for="timeFrame" class="time-frame-label">Time Frame:</label>
                    <select id="timeFrame" class="time-frame-select">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                    <label for="timeFrame" class="time-frame-label">Chart Type:</label>
                    <select id="chartType" class="time-frame-select">
                        <option value="bar">Bar</option>
                        <option value="line">Line</option>
                        <option value="pie">Pie</option>
                        <option value="doughnut">Doughnut</option>
                    </select>

                    <canvas id="appointmentsChart"></canvas>
                </div>

            </div>
        </main>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
        const ctx = document.getElementById('appointmentsChart').getContext('2d');
        let appointmentsChart;

        // Track the current selections
        let currentTimeFrame = 'daily'; // Default time frame
        let currentChartType = 'bar'; // Default chart type

        // Function to fetch data and render the chart
        async function fetchAndRenderChart(timeFrame = currentTimeFrame, chartType = currentChartType) {
            try {
                const response = await fetch(`/project_wad/backend/admin/get_appointments.php?time_frame=${timeFrame}`);
                const data = await response.json();

                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                let labels = [];
                let totals = [];

                if (timeFrame === 'daily') {
                    labels = data.map(item => item.date);
                    totals = data.map(item => item.total);
                } else if (timeFrame === 'weekly') {
                    labels = data.map(item => item.week_range).reverse(); // Reverse labels for weekly
                    totals = data.map(item => item.total).reverse(); // Reverse totals for weekly
                } else if (timeFrame === 'monthly') {
                    labels = data.map(item => item.month); // Use month names for labels
                    totals = data.map(item => item.total);
                }

                // Destroy the chart if it already exists
                if (appointmentsChart) {
                    appointmentsChart.destroy();
                }

                // Create the chart
                appointmentsChart = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Appointments (${timeFrame})`,
                            data: totals,
                            backgroundColor: chartType === 'pie' || chartType === 'doughnut' ? [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                                'rgba(153, 102, 255, 0.5)',
                                'rgba(255, 159, 64, 0.5)',
                            ] : 'rgba(202, 214, 255, 0.5)',
                            borderColor: 'rgb(109, 139, 235)',
                            borderWidth: 1.5,
                            tension: chartType === 'line' ? 0.4 : 0, // Smooth lines for line chart
                        }],
                    },
                    options: {
                        responsive: true,
                        scales: chartType === 'pie' || chartType === 'doughnut' ? {} : { // Disable scales for pie/doughnut
                            y: {
                                beginAtZero: true,
                            },
                        },
                        animation: {
                            duration: 1500,
                        },
                    },
                });
            } catch (error) {
                console.error('Error fetching or rendering chart:', error);
            }
        }

        // Event listener for time frame selection
        document.getElementById('timeFrame').addEventListener('change', (event) => {
            currentTimeFrame = event.target.value; // Update the current time frame
            fetchAndRenderChart(currentTimeFrame, currentChartType);
        });

        // Event listener for chart type selection
        document.getElementById('chartType').addEventListener('change', (event) => {
            currentChartType = event.target.value; // Update the current chart type
            fetchAndRenderChart(currentTimeFrame, currentChartType);
        });

        // Initial chart load
        fetchAndRenderChart();
    </script>

</body>

</html>