<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Initialize $payments variable
$payments = [];

// Fetch payment data ordered by the latest payment_date
$query = "SELECT payment_id, amount, payment_date, receipt_id FROM payments ORDER BY payment_date DESC";
$result = $conn->query($query);

// Check if the query succeeded
if ($result && $result->num_rows > 0) {
    // Fetch all payment records as an associative array
    $payments = $result->fetch_all(MYSQLI_ASSOC);
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment List</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/payment_list.css">
    <style>
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
        <h1>Dashboard / Payment list</h1>
        <h2>Payment List</h2>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/admin/dashboard/admin_dashboard.php">Dashboard</a>
                <a href="/project_wad/frontend/admin/members/member_list.php">Member List</a>
                <a href="#">Appointment</a>
                <a href="/project_wad/frontend/admin/payment/payment_list.php">Payment List</a>
                <a href="#">Services</a>
                <a href="/project_wad/frontend/admin/activities/manage_activities.php">Activities</a>
                <a href="#">Doctors</a>
                <a href="/project_wad/frontend/admin/promotions/manage_promotions.php">Promotions</a>
                <a href="logout.php">Log Out</a>
            </nav>
        </aside>

        <div class="main-content">
            <h1>Payments Overview</h1>
            <div class="select-container">
                <label for="timeFrame">Time Frame:</label>
                <select id="timeFrame">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                </select>
            </div>

            <!-- Canvas for Chart.js -->
            <canvas id="paymentsChart" width="400" height="200"></canvas>

            <section class="transaction-summary">
                <h2>Transaction Summary</h2>
                <div class="select-container">
                    <label for="summaryTimeFrame">Filter By:</label>
                    <select id="summaryTimeFrame">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Total Transactions</th>
                            <th>Total Amount (RM)</th>
                        </tr>
                    </thead>
                    <tbody id="summaryTableBody">
                        <!-- Rows will be added dynamically -->
                    </tbody>
                </table>
            </section>

            <section class="payment-list">
                <h2>Recent Payments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Receipt ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($payments) > 0): ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                                    <td>RM<?php echo number_format($payment['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($payment['payment_date']))); ?></td>
                                    <td><?php echo htmlspecialchars($payment['receipt_id']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No payments found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="/project_wad/javascript/transaction_summary.js"></script>

    <script>
        const ctx = document.getElementById('paymentsChart').getContext('2d');
        let paymentsChart;

        // Function to fetch data and render the chart
        async function fetchAndRenderChart(timeFrame = 'daily') {
            try {
                const response = await fetch(`http://localhost/project_wad/backend/admin/get_payments.php?time_frame=${timeFrame}`);
                const data = await response.json();

                // Extract labels and totals
                const labels = data.map(item => item.week_range || item.date || item.month);
                const totals = data.map(item => item.total);

                // Destroy the existing chart if it exists
                if (paymentsChart) {
                    paymentsChart.destroy();
                }

                // Create the chart
                paymentsChart = new Chart(ctx, {
                    type: 'bar', // Chart type (bar chart)
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `Payments (${timeFrame})`,
                            data: totals,
                            backgroundColor: 'rgba(108, 192, 192, 0.2)',
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