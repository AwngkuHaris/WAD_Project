<?php

include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $specialty = $_POST['specialty'] ?? '';
    $image = $_FILES['image']['name'] ?? '';

    // Validate inputs
    if (!$name || !$specialty) {
        die("Error: Name and Specialty are required.");
    }

    if (!$image) {
        die("Error: Image is required.");
    }

    // Save image to target directory
    $target_dir = "../../../images/doctors/";
    $target_file = $target_dir . basename($image);

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        die("Error: Failed to upload image.");
    }

    // Insert into database
    $sql = "INSERT INTO doctors (name, specialty, image) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param('sss', $name, $specialty, $image);

    if ($stmt->execute()) {
        header("Location: manage_doctors.php");
        exit;
    } else {
        die("Database Error: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/doctors.css">
    <title>Add Doctor</title>
</head>
<body>
    <!-- Header -->
    <?php include('../../../header.php'); ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="manage_doctors.php">Manage Doctors</a> / Add Doctor</p>
            <h1>Add Doctor</h1>
        </div>
    </section>

    <div class="content">
    <div class="blog-posts">
    <h1>Add New Doctor</h1>
    <form action="" method="POST" enctype="multipart/form-data" class="doctor-form-container">
        <label for="image">Image:</label>
        <input type="file" name="image" id="image">
        <hr>

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" placeholder="Enter doctor name">
        <hr>

        <label for="specialty">Specialty:</label>
        <input type="text" name="specialty" id="specialty" placeholder="Enter Doctor Specialty">
        <hr>

        <button type="submit" class="read-more">Add Doctor</button>
    </form>
</div>
    </div>

    <!-- Footer -->
    <?php include('../../../footer.php'); ?>
</body>
</html>
