<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch all services
$query = "SELECT * FROM services";
$result = $conn->query($query);
$services = [];

if ($result && $result->num_rows > 0) {
    $services = $result->fetch_all(MYSQLI_ASSOC); // Fetch all services as an associative array
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="/project_wad/styles/services.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="/project_wad/index.php">Home</a> / Services</p>
            <h1>Our Services</h1>
        </div>
    </section>

    <div class="filter-container">
        <input type="text" id="filterInput" placeholder="Search services by name...">
    </div>

    <div class="services-container">
        <div id="servicesGrid" class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <div class="service-image">
                        <img src="/project_wad/images/services/<?php echo htmlspecialchars($service['image']); ?>"
                            alt="<?php echo htmlspecialchars($service['service_name']); ?>">
                    </div>
                    <div class="service-details">
                        <h2><?php echo htmlspecialchars($service['service_name']); ?></h2>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <a href="service_details.php?service_id=<?php echo $service['service_id']; ?>" class="learn-more">Learn More</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="/project_wad/javascript/search_services.js"></script>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>

</body>

</html>