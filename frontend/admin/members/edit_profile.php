<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';
session_start();

// Fetch user ID from the query string
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// Redirect back if user_id is not provided
if ($user_id === 0) {
    header("Location: /project_wad/frontend/admin/members/member_list.php");
    exit();
}

// Fetch user details
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $fullName = htmlspecialchars($_POST['fullName']);
    $email = htmlspecialchars($_POST['email']);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $state = htmlspecialchars($_POST['state']);
    $postcode = intval($_POST['postcode']);
    $country = htmlspecialchars($_POST['country']);

    // Update user details
    $update_query = "
        UPDATE users 
        SET fullName = ?, email = ?, contactNumber = ?, address = ?, city = ?, state = ?, postcode = ?, country = ?
        WHERE user_id = ?
    ";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param(
        "ssssssisi",
        $fullName,
        $email,
        $contactNumber,
        $address,
        $city,
        $state,
        $postcode,
        $country,
        $user_id
    );

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = '/project_wad/frontend/admin/members/view_profile.php?user_id={$user_id}';</script>";
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/edit_profile.css">
    <title>Edit Profile</title>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>
    <div class="edit-profile-container">
        <h1>Edit Member Profile</h1>
        <form method="POST">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($user['contactNumber']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>

            <label for="postcode">Postcode:</label>
            <input type="number" id="postcode" name="postcode" value="<?php echo htmlspecialchars($user['postcode']); ?>" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" required>

            <div class="actions">
                <button class="save-btn" type="submit">Save Changes</button>
                <button class="cancel-btn" type="button" onclick="window.location.href='/project_wad/frontend/admin/members/view_profile.php?user_id=<?php echo $user_id; ?>'">Cancel</button>
            </div>

        </form>
    </div>
</body>

</html>