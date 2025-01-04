<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    // Upload image
    $target_dir = "../../../images/services/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert service into database
    $sql = "INSERT INTO services (service_name, description, price, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $service_name, $description, $price, $image);
    $stmt->execute();

    header("Location: services.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/services.css">
    <title>Create Service</title>
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>

    <!-- Breadcrumb Section -->
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="services.php">Manage Services</a> / Create Service</p>
            <h1>Create Service</h1>
        </div>
    </section>

    <h1>Add New Service</h1>

    <div class="content">
        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data" class="form-container">
                <label for="service_name">Service Name:</label>
                <input type="text" id="service_name" name="service_name" required class="form-input">

                <label for="description">Description:</label>
                <textarea id="description" name="description" required class="form-input"></textarea>

                <label for="price">Price (RM):</label>
                <input type="number" id="price" name="price" step="1" required class="form-input">

                <label for="image">Image:</label>
                <input type="file" id="image" name="image" required class="form-input">

                <button type="submit" class="read-more">Create Service</button>
            </form>
        </div>
    </div>

    <?php include('../../../footer.php'); ?>
</body>

</html>