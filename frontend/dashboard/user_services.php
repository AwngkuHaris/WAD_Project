<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if the user is not logged in
    exit();
}

include '../../backend/db_connect.php'; // Include database connection

// Fetch services
$query = "SELECT service_id, service_name, description, price FROM services";
$result = $conn->query($query);
$services = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Service List</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/user_services.css">
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
                <a href="/project_wad/frontend/dashboard/dashboard.php">Dashboard</a>
                <a href="#">Profile</a>
                <a href="/project_wad/frontend/dashboard/user_services.php">Services</a>
                <a href="/project_wad/frontend/dashboard/book_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/dashboard/payment_page.php">Payments</a>
                <a href="/project_wad/frontend/dashboard/user_cart.php">Cart</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>

        <main class="services-container">

            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                    <div class="service-card">
                        <div class="service-details">
                            <h2><?php echo htmlspecialchars($service['service_name']); ?></h2>
                            <p><?php echo htmlspecialchars($service['description']); ?></p>
                            <p><strong>Price:</strong> RM<?php echo number_format($service['price'], 2); ?></p>
                            <a href="book_appointment.php?service_id=<?php echo $service['service_id']; ?>" class="learn-more">Book Appointment</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <script src="/project_wad/javascript/search_services.js"></script>
</body>

</html>