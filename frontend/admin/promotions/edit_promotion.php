<?php
// Database connection
require_once '../../../backend/db_connect.php';

// Get promotion ID from URL
$promotion_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch promotion details
$sql = "SELECT * FROM promotions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $promotion_id);
$stmt->execute();
$result = $stmt->get_result();
$promotion = $result->fetch_assoc();

// If promotion not found, display an error
if (!$promotion) {
    echo "Promotion not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image = $promotion['image']; // Default to current image

    // If a new image is uploaded, update it
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../../../images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // Update promotion in the database
    $sql = "UPDATE promotions SET title = ?, description = ?, image = ?, start_date = ?, end_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $description, $image, $start_date, $end_date, $promotion_id);
    $stmt->execute();

    // Redirect to manage promotions page
    header("Location: manage_promotions.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../styles/activities.css">
    <title>Edit Promotion</title>
</head>
<body>
    <?php include('../../../header.php'); ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
        <p><a href="dashboard.php">Dashboard</a> / <a href="manage_promotions.php">Manage Activities</a> / Edit Promotion</p>
        <h1>Edit Promotion</h1>
        </div>
    </section>

    <div class="content">
        <div class="blog-posts">
            <div class="post">
                <div class="post-details">
                    <h1>Edit Promotion</h1>
                    <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                        <!-- Image -->
                        <label for="image">Current Image:</label>
                        <img src="../../../images/<?php echo htmlspecialchars($promotion['image']); ?>" 
                            alt="<?php echo htmlspecialchars($promotion['title']); ?>" 
                            class="full-width">
                        <label for="image">Upload New Image:</label>
                        <input type="file" id="image" name="image" class="form-input">
                        <hr>

                        <!-- Title -->
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($promotion['title']); ?>" required class="form-input">
                        <hr>

                        <!-- Description -->
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required class="form-input"><?php echo htmlspecialchars($promotion['description']); ?></textarea>
                        <hr>

                        <!-- Start Date -->
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($promotion['start_date']); ?>" required class="form-input">
                        <hr>

                        <!-- End Date -->
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($promotion['end_date']); ?>" required class="form-input">
                        <hr>

                        <!-- Submit Button -->
                        <button type="submit" class="read-more">Update Promotion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>
</body>
</html>
