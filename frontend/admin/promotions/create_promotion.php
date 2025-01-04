<?php
require_once '../../../backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $image = $_FILES['image']['name'];

    $target_dir = "../../../images/promotions/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    $sql = "INSERT INTO promotions (title, description, image, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $description, $image, $start_date, $end_date);
    $stmt->execute();

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
    <title>Create Promotion</title>
</head>
<body>
    <?php include('../../../header.php'); ?>

     <!-- Breadcrumb Section -->
     <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="manage_promotions.php">Manage Promotions</a> / Create Promotion</p>
            <h1>Create Promotions</h1>
        </div>
    </section>


    <div class="content">
        <div class="blog-posts">
            <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required class="form-input">

                <label for="description">Description:</label>
                <textarea id="description" name="description" required class="form-input"></textarea>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required class="form-input">

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required class="form-input">

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required class="form-input">

                <button type="submit" class="read-more">Create Promotion</button>
            </form>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>
</body>
</html>
