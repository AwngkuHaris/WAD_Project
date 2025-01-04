<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Search functionality
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = $_GET['search_query'];

    // Search query to filter doctors based on name or specialty
    $sql = "SELECT * FROM doctors WHERE name LIKE ? OR specialty LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $like_query = '%' . $search_query . '%';
    $stmt->bind_param("ss", $like_query, $like_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch all doctors
    $sql = "SELECT * FROM doctors ORDER BY id DESC";
    $result = $conn->query($sql);
}

// Fetch recent doctors
$recent_sql = "SELECT id, name FROM doctors ORDER BY id DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor List</title>
    <link rel="stylesheet" href="/project_wad/styles/doctor_list.css">
</head>
<body>
    <!-- Header -->
    <?php include('../../header.php'); ?>

    <!-- Breadcrumb Section -->
   <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="index.php">Home</a> / Doctors</p>
            <h1>Doctor List</h1>
        </div>
    </section>

    <div class="doctor-list-container">
        <h1>Our Doctors</h1>
        <div class="doctor-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="doctor-card">
                        <img src="../../images/doctors/<?php echo htmlspecialchars($row['image']); ?>" alt="Doctor Image">
                        <div class="doctor-info">
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['specialty']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No doctors found.</p>
            <?php endif; ?>
        </div>
    </div>


    <?php include '../../footer.php'; ?>
</body>
</html>
