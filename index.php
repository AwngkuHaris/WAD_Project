<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch doctors' data
$queryDoctors = "SELECT name, specialty, image FROM doctors";
$resultDoctors = $conn->query($queryDoctors);

// Check if the query succeeded for doctors
$doctors = [];
if ($resultDoctors && $resultDoctors->num_rows > 0) {
    $doctors = $resultDoctors->fetch_all(MYSQLI_ASSOC);
}

// Fetch promotions data
$queryPromotions = "
    SELECT id, title, description, image, start_date, end_date 
    FROM promotions 
    WHERE CURDATE() BETWEEN start_date AND end_date
    ORDER BY start_date DESC
";
$resultPromotions = $conn->query($queryPromotions);

// Check if the query succeeded for promotions
$promotions = [];
if ($resultPromotions && $resultPromotions->num_rows > 0) {
    $promotions = $resultPromotions->fetch_all(MYSQLI_ASSOC);
}

// Fetch activities data
$queryActivities = "
    SELECT activity_id, title, description, image, posted_date, author 
    FROM activities 
    ORDER BY posted_date DESC
";
$resultActivities = $conn->query($queryActivities);

// Check if the query succeeded for activities
$activities = [];
if ($resultActivities && $resultActivities->num_rows > 0) {
    $activities = $resultActivities->fetch_all(MYSQLI_ASSOC);
}

// Close the connection
$conn->close();
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANMAS Dental Specialist Clinic</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/index.css">

</head>

<body>
    <?php include 'header.php'; ?>

    <div class="hero-section">
        <h2 class="ml3">Your Trusted Specialist Surgeon</h2>
        <h1 class="ml2">A Smile For Your Future</h1>
    </div>

    <div class="action-buttons">
        <a href="/project_wad/frontend/services/services.php" class="action-button">Find a Service</a>
        <a href="/project_wad/frontend/doctors/doctor_list.php" class="action-button">Doctors</a>
        <a href="/project_wad/frontend/promotions/promotions.php" class="action-button">Promotions</a>
        <a href="/project_wad/frontend/dashboard/dashboard.php" class="action-button">Login</a>
    </div>

    <div class="welcome-section">
        <h2>Welcome to ANMAS Dental Specialist Clinic</h2>
        <h3>A Great Place to Receive Care</h3>
        <p>At Anmas, we're committed to providing exceptional dental care for you and your family. Let us help you achieve the smile you've always dreamed of.</p>
        <a href="/project_wad/frontend/about_us/about_us.php" class="learn-more">Learn More</a>

        
    </div>

    <div class="specialties-section">
        <h2>Our Specialties</h2>

        <div class="specialties-grid">
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_implant.png" alt="Implant">
                <p>Implant</p>
                <div class="specialty-info">
                    <p>Dental implants provide a foundation for replacement teeth that look, feel, and function like natural teeth.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_crown.png" alt="Crown">
                <p>Crown</p>
                <div class="specialty-info">
                    <p>Dental crowns are a cap placed over a damaged tooth to restore its shape, size, and strength.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_bridge.png" alt="Bridge">
                <p>Bridge</p>
                <div class="specialty-info">
                    <p>A dental bridge is used to replace missing teeth, effectively bridging the gap.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_filling.png" alt="Filling">
                <p>Filling</p>
                <div class="specialty-info">
                    <p>Fillings are used to repair cavities and restore damaged teeth.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_scaling.png" alt="Scaling">
                <p>Scaling</p>
                <div class="specialty-info">
                    <p>Scaling removes plaque and tartar buildup from teeth and gums to prevent gum disease.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_whitening.png" alt="whitening">
                <p>Whitening</p>
                <div class="specialty-info">
                    <p>Teeth whitening treatments enhance the brightness of your smile by removing stains.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_braces.png" alt="braces">
                <p>Braces</p>
                <div class="specialty-info">
                    <p>Braces help align and straighten teeth to improve dental health and appearance.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/orthodontic.png" alt="Orthodontic Treatment">
                <p>Orthodontic Treatment</p>
                <div class="specialty-info">
                    <p>Orthodontic treatment corrects misaligned teeth and jaw positioning for better functionality.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_inlay.png" alt="Inlay">
                <p>Inlay</p>
                <div class="specialty-info">
                    <p>Inlays repair the chewing surface of decayed or fractured teeth.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/teeth_prosthetic.png" alt="Prosthetic">
                <p>Prosthetic</p>
                <div class="specialty-info">
                    <p>Prosthetic dentistry replaces missing teeth with artificial restorations such as dentures.</p>
                </div>
            </div>
            <div class="specialty-item">
                <img class="specialty-image" src="images/services/endodontis.png" alt="Endodontis">
                <p>Endodontis</p>
                <div class="specialty-info">
                    <p>Endodontic treatment involves root canal therapy to save infected or damaged teeth.</p>
                </div>
            </div>

        </div>
    </div>


    <div class="doctors-section">
        <h4>Trusted Care</h4>
        <h2>Our Doctors</h2>

        <div class="doctors-grid">
            <?php if (!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-card">
                        <div class="doctor-image">
                            <img src="/project_wad/images/doctors/<?php echo htmlspecialchars($doctor['image']); ?>" alt="Doctor Image">
                        </div>
                        <div class="doctor-info">
                            <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                            <p><?php echo htmlspecialchars($doctor['specialty']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No doctors available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="activities-section">
        <h4>Better Information, Better Health</h4>
        <h2>Activities</h2>
        <div class="activities-container">
            <?php foreach ($activities as $activity): ?>
                <div class="activities-box">
                    <div class="left">
                        <img src="/project_wad/images/activities/<?php echo htmlspecialchars($activity['image']); ?>" alt="Activity Image">
                    </div>
                    <div class="right">
                        <p class="meta"><?php echo date('l d, F Y', strtotime($activity['posted_date'])); ?> | By <?php echo htmlspecialchars($activity['author']); ?></p>
                        <h2 class="title"><?php echo htmlspecialchars($activity['title']); ?></h2>
                        <p class="description"><?php echo htmlspecialchars($activity['description']); ?></p>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>


    <div class="promotions-section">
        <h2>Promotions</h2>
        <div class="promotions-container">
            <?php foreach ($promotions as $promotion): ?>
                <div class="promotion-box">
                    <div class="left">
                        <!-- Display promotion image -->
                        <img src="/project_wad/images/promotions/<?php echo htmlspecialchars($promotion['image']); ?>" alt="<?php echo htmlspecialchars($promotion['title']); ?>" class="promotion-image">
                    </div>
                    <div class="right">
                        <p class="meta"><?php echo htmlspecialchars(date('d/m/Y', strtotime($promotion['start_date']))); ?> - <?php echo htmlspecialchars(date('d/m/Y', strtotime($promotion['end_date']))); ?></p>
                        <h2 class="title"><?php echo htmlspecialchars($promotion['title']); ?></h2>
                        <p class="description"><?php echo htmlspecialchars($promotion['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="contact-section">
        <div class="contact-container">
            <div class="contact-box">
                <h2>Contact Number</h2>
                <p>019 - 400 1241</p>
            </div>
            <div class="contact-box">
                <h2>Address</h2>
                <p>31, Lor Uni Garden 1, 94300 Kota Samarahan, Sarawak</p>
            </div>
            <div class="contact-box">
                <h2>Social Media</h2>

                <a href="https://www.facebook.com/p/Klinik-Pergigian-Anmas-Samarahan-61557534173385/" target="_blank" class="social-link" aria-label="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
            </div>
            <div class="contact-box">
                <h2>Working Hour</h2>
                <p>Monday To Friday,</p>
                <p>8 am - 1 pm, 2 pm - 6 pm</p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <script src="javascript/index.js"></script>


    <?php include 'footer.php'; ?>


</body>

</html>