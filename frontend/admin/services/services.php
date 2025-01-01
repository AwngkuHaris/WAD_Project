<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch all services
$query = "SELECT service_id, service_name, price FROM services";
$result = $conn->query($query);
$services = [];

if ($result && $result->num_rows > 0) {
    $services = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services List</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/services.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

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

        <div class="services-table-container">
            <h1>Services List</h1>
            <div class="create-service-button">
                <a href="create_service.php" class="create-button">Add New Service</a>
            </div>

            <table>
                <thead>
                    <tr>

                        <th>Service Name</th>
                        <th>Price (RM)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($services) > 0): ?>
                        <?php foreach ($services as $service): ?>
                            <tr>

                                <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                                <td>RM<?php echo number_format($service['price'], 2); ?></td>
                                <td>
                                    <a href="edit_service.php?id=<?php echo $service['service_id']; ?>" class="edit-button">Edit Service</a>
                                    <a href="delete_service.php?id=<?php echo $service['service_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this service?');">Delete Service</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No services found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>