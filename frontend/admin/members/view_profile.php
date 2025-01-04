<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Check if user_id is provided in the URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    echo "<script>alert('User ID is missing.'); window.location.href = '/project_wad/frontend/admin/members/member_list.php';</script>";
    exit;
}

$user_id = intval($_GET['user_id']);

// Fetch user details from the database
$query = "SELECT 
            fullName, 
            email, 
            contactNumber, 
            role, 
            created_at, 
            myKadNumber, 
            dateOfBirth, 
            gender, 
            address, 
            city, 
            state, 
            postcode, 
            country 
          FROM users 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If no user found, redirect back
if (!$user) {
    echo "<script>alert('User not found.'); window.location.href = '/project_wad/frontend/admin/members/member_list.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/view_profile.css">
    <title>View Profile</title>
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>
    <div class="profile-container">
        <h1>Member Profile</h1>
        <div class="profile-details">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contactNumber']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
            <p><strong>Joined On:</strong> <?php echo htmlspecialchars(date('d-m-Y', strtotime($user['created_at']))); ?></p>
            <p><strong>MyKad Number:</strong> <?php echo htmlspecialchars($user['myKadNumber']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars(date('d-m-Y', strtotime($user['dateOfBirth']))); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
            <p><strong>State:</strong> <?php echo htmlspecialchars($user['state']); ?></p>
            <p><strong>Postcode:</strong> <?php echo htmlspecialchars($user['postcode']); ?></p>
            <p><strong>Country:</strong> <?php echo htmlspecialchars($user['country']); ?></p>
        </div>
        <div class="actions">
            <button class="back-btn" onclick="window.location.href='/project_wad/frontend/admin/members/member_list.php'">Back</button>
            <!-- Edit Button -->
            <button class="edit-btn" onclick="location.href='/project_wad/frontend/admin/members/edit_profile.php?user_id=<?php echo $user_id; ?>'">
                Edit Profile
            </button>
        </div>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>

</html>