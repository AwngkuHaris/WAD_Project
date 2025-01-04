<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

$doctor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM doctors WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "Doctor not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialty = $_POST['specialty'];
    $image = $doctor['image'];

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../../../images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    $sql = "UPDATE doctors SET name = ?, specialty = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $specialty, $image, $doctor_id);
    $stmt->execute();

    header("Location: manage_doctors.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/doctor_list.css">
    <title>Edit Doctor</title>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>

    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="dashboard.php">Dashboard</a> / <a href="manage_doctors.php">Manage Doctors</a> / Edit Doctor</p>
            <h1>Edit Doctor</h1>
        </div>
    </section>

    <div class="content">
        <div class="form-container">
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($doctor['name']); ?>" required>

                <label for="specialty">Specialty:</label>
                <input type="text" id="specialty" name="specialty" value="<?php echo htmlspecialchars($doctor['specialty']); ?>" required>

                <label for="image">Current Image:</label>
                <div class="image-preview">
                    <img src="../../../images/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor Image">
                </div>
                <input type="file" id="image" name="image">

                <button type="submit" class="btn btn-primary">Update Doctor</button>
            </form>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>
</html>
