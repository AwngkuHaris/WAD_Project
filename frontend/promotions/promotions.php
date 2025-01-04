<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch all promotions
$query = "SELECT * FROM promotions ORDER BY created_at DESC";
$result = $conn->query($query);
$promotions = [];

if ($result && $result->num_rows > 0) {
    $promotions = $result->fetch_all(MYSQLI_ASSOC); // Fetch all promotions as an associative array
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions</title>
    <link rel="stylesheet" href="/project_wad/styles/promotions.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="/index.php">Home</a> / Promotions</p>
            <h1>Current Promotions</h1>
        </div>
    </section>

    <div class="promotions-container">
        <?php if (!empty($promotions)): ?>
            <?php foreach ($promotions as $promo): ?>
                <div class="promotion-card">
                    <img src="/project_wad/images/promotions/<?php echo htmlspecialchars($promo['image']); ?>" alt="<?php echo htmlspecialchars($promo['title']); ?>">
                    <div class="promotion-details">
                        <h2><?php echo htmlspecialchars($promo['title']); ?></h2>
                        <p><?php echo htmlspecialchars($promo['description']); ?></p>
                        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($promo['start_date']); ?></p>
                        <p><strong>End Date:</strong> <?php echo htmlspecialchars($promo['end_date']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No promotions are currently available.</p>
        <?php endif; ?>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>

</html>
