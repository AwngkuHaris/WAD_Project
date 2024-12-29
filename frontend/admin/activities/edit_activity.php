<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Get activity ID from URL
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

// Fetch activity details
$sql = "SELECT * FROM activities WHERE activity_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result();
$activity = $result->fetch_assoc();

// If activity not found, display an error
if (!$activity) {
    echo "Activity not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author = $_POST['author'];
    $image = $activity['image']; // Default to current image

    // If a new image is uploaded, update it
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../../images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // Update activity in the database
    $sql = "UPDATE activities SET title = ?, description = ?, image = ?, author = ? WHERE activity_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $image, $author, $activity_id);
    $stmt->execute();

    // Redirect to manage activities page
    header("Location: manage_activities.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/activities.css">
    <title>Edit Activity</title>
</head>
<body>
    <!-- Header -->
    <?php include('../../../header.php'); ?>

     <!-- Breadcrumb Section -->
     <section class="breadcrumb">
        <div class="breadcrumb-container">
        <p><a href="dashboard.php">Dashboard</a> / <a href="manage_activities.php">Manage Activities</a> / Edit Activity</p>
        <h1>Edit Activities</h1>
        </div>
    </section>

    <div class="content">
        <div class="blog-posts">
            <div class="post">
                <div class="post-details">
                    <h1>Edit Activity</h1>
                    <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                        <!-- Image -->
                        <label for="image">Current Image:</label>
                        <img src="../../images/<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>" style="width: 100%; max-height: 200px; object-fit: cover; margin-bottom: 10px;">
                        <label for="image">Upload New Image:</label>
                        <input type="file" id="image" name="image" class="form-input">
                        <hr>

                        <!-- Title -->
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($activity['title']); ?>" required class="form-input">
                        <hr>

                        <!-- Description -->
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required class="form-input"><?php echo htmlspecialchars($activity['description']); ?></textarea>
                        <hr>

                        <!-- Author -->
                        <label for="author">Author:</label>
                        <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($activity['author']); ?>" required class="form-input">
                        <hr>

                        <!-- Submit Button -->
                        <button type="submit" class="read-more">Update Activity</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include('../../../footer.php'); ?>
</body>
</html>
