<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch all activities
$sql = "SELECT * FROM activities ORDER BY posted_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_activities.css?v=1.0">
    <title>Manage Activities</title>
</head>

<body>
    <!-- Header -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / Manage Activities</p>
            <h1>Manage Activities</h1>
        </div>
    </section>

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
        <!-- Main Content Section -->
        <div class="content">
            <div class="blog-posts">
                <div style="text-align: center; margin-bottom: 20px;">
                    <a href="create_activity.php" class="create-button" style="margin-bottom: 20px;">Create New Activity</a>
                </div>
                <!-- Table for Activities -->
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Posted Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['activity_id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                                    <td><?php echo date('F j, Y', strtotime($row['posted_date'])); ?></td>
                                    <td>
                                        <a href="edit_activity.php?activity_id=<?php echo $row['activity_id']; ?>" class="edit-button">Edit</a>
                                        <a href="delete_activity.php?activity_id=<?php echo $row['activity_id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this activity?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No activities found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>

</body>

</html>