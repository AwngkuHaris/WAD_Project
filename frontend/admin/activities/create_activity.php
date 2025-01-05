<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $image = $_FILES['image']['name'];
    $author = $_POST['author'];

    // Upload image
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/project_wad/images/activities/';
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert activity into database
    $sql = "INSERT INTO activities (title, description, image, author) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $image, $author);
    $stmt->execute();

    header("Location: manage_activities.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/admin_activities.css?v=1.0">
    <title>Create Activity</title>
</head>
<body>
    <!-- Header -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

     <!-- Breadcrumb Section -->
     <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="manage_activities.php">Manage Activities</a> / Create Activity</p>
            <h1>Create Activities</h1>
        </div>
    </section>

    <div class="box_content">
    <div class="blog-posts">
        <div class="post">
            <div class="post-details">
            <h2 style="text-align: center;">Create New Activity</h2>
                <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                    <!-- Image Upload -->
                    <label for="image">Image:</label>
                    <input type="file" id="image" name="image" required class="form-input">
                    <hr>

                    <!-- Title -->
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" placeholder="Enter activity title" required class="form-input">
                    <hr>

                    <!-- Description -->
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Enter activity description" required class="form-input"></textarea>
                    <hr>

                    <!-- Author -->
                    <label for="author">Author:</label>
                    <input type="text" id="author" name="author" placeholder="Enter author name" required class="form-input">
                    <hr>

                    <!-- Submit Button -->
                    <button type="submit" class="read-more">Create Activity</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>
</html>
