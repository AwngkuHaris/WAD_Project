<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Retrieve and validate the activity ID
$activity_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($activity_id <= 0) {
    die("Invalid activity ID.");
}

// Prepare and execute the query
$sql = "SELECT * FROM activities WHERE activity_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the activity or display an error if not found
if ($result->num_rows > 0) {
    $activity = $result->fetch_assoc();
    // Display activity details below (e.g., title, description, etc.)
} else {
    die("Activity not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/activities.css">
    <title><?php echo htmlspecialchars($activity['title']); ?></title>
</head>
<body>
    <!-- Header Section -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="/project_wad/index.php">Home</a> / <a href="/project_wad/frontend/activities/activities.php">Activities</a> / <?php echo htmlspecialchars($activity['title']); ?></p>
            <h1><?php echo htmlspecialchars($activity['title']); ?></h1>
        </div>
    </section>

     <!-- Activity Details -->
     <div class="vm_content">
        <div class="blog-posts">
            <div class="post">
                <!-- Post Thumbnail -->
                <div class="vm_post-thumbnail">
                    <img src="/project_wad/images/activities/<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>">
                </div>

                <!-- Post Details -->
                <div class="post-details">
                    <div class="vm_post-meta">
                        Posted on <?php echo date('F j, Y', strtotime($activity['posted_date'])); ?> by <?php echo htmlspecialchars($activity['author']); ?>
                    </div>
                    <p class="vm_post-excerpt">
                        <?php echo nl2br(htmlspecialchars($activity['description'])); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>
</html>
