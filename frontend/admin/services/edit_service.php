<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Get service ID from URL
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch service details
$query = "SELECT * FROM services WHERE service_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    echo "Service not found.";
    exit;
}

// Update service
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_name = $_POST['service_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $update_query = "UPDATE services SET service_name = ?, price = ?, description = ? WHERE service_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sdsi", $service_name, $price, $description, $service_id);

    if ($update_stmt->execute()) {
        header("Location: /project_wad/frontend/admin/services/services.php");
        exit;
    } else {
        echo "Error updating service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="/project_wad/styles/admin/services.css">
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>

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
        <div class="container">
            <h1>Edit Service</h1>
            <form method="POST">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" value="<?php echo htmlspecialchars($service['service_name']); ?>" required>

                <label for="price">Price (RM):</label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($service['price']); ?>" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($service['description']); ?></textarea>

                <button type="submit">Update Service</button>
            </form>

            <a href="/project_wad/frontend/admin/services/services.php" class="back-button">Back to Service List</a>
        </div>
    </div>
</body>

</html>