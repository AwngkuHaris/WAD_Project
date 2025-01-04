<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT fullName, myKadNumber, dateOfBirth, contactNumber, email, gender, address, city, state, postcode, country FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize inputs
    $fullName = htmlspecialchars($_POST['fullName'], ENT_QUOTES, 'UTF-8');
    $contactNumber = htmlspecialchars($_POST['contactNumber'], ENT_QUOTES, 'UTF-8');
    $gender = $_POST['gender'];
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
    $state = htmlspecialchars($_POST['state'], ENT_QUOTES, 'UTF-8');
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];

    // Update user details
    $stmt = $conn->prepare("
        UPDATE users 
        SET fullName = ?, contactNumber = ?, gender = ?, address = ?, city = ?, state = ?, postcode = ?, country = ? 
        WHERE user_id = ?
    ");
    $stmt->bind_param("ssssssssi", $fullName, $contactNumber, $gender, $address, $city, $state, $postcode, $country, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Failed to update profile. Please try again.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/edit_profile.css">
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/user_header.php'; ?>
    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <div class="profile-container">
        <h1>Edit Your Profile</h1>
        <form method="POST" action="">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" required>

            <label for="contactNumber">Contact Number:</label>
            <input type="text" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($user['contactNumber']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label for="city">City:</label>
            <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>

            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" value="<?php echo htmlspecialchars($user['postcode']); ?>" required>

            <label for="country">Country:</label>
            <select id="country" name="country" required>
                <option value="Malaysia" <?php if ($user['country'] === 'Malaysia') echo 'selected'; ?>>Malaysia</option>
            </select>

            <button type="submit" class="submit-btn">Update Profile</button>
        </form>
    </div>
</body>

</html>