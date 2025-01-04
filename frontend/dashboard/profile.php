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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/profile.css">
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/user_header.php'; ?>
    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/dashboard/dashboard.php">Dashboard</a>
                <a href="#">Profile</a>
                <a href="/project_wad/frontend/dashboard/user_services.php">Services</a>
                <a href="/project_wad/frontend/dashboard/book_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/dashboard/payment_page.php">Payments</a>
                <a href="/project_wad/frontend/dashboard/user_cart.php">Cart</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>
        <div class="profile-container">
            <h1>Your Profile</h1>
            <table class="profile-table">
                <tr>
                    <th>Full Name:</th>
                    <td><?php echo htmlspecialchars($user['fullName']); ?></td>
                </tr>
                <tr>
                    <th>MyKad Number:</th>
                    <td><?php echo htmlspecialchars($user['myKadNumber']); ?></td>
                </tr>
                <tr>
                    <th>Date of Birth:</th>
                    <td><?php echo htmlspecialchars($user['dateOfBirth']); ?></td>
                </tr>
                <tr>
                    <th>Contact Number:</th>
                    <td><?php echo htmlspecialchars($user['contactNumber']); ?></td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td><?php echo htmlspecialchars($user['gender']); ?></td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td><?php echo htmlspecialchars($user['address']); ?></td>
                </tr>
                <tr>
                    <th>City:</th>
                    <td><?php echo htmlspecialchars($user['city']); ?></td>
                </tr>
                <tr>
                    <th>State:</th>
                    <td><?php echo htmlspecialchars($user['state']); ?></td>
                </tr>
                <tr>
                    <th>Postcode:</th>
                    <td><?php echo htmlspecialchars($user['postcode']); ?></td>
                </tr>
                <tr>
                    <th>Country:</th>
                    <td><?php echo htmlspecialchars($user['country']); ?></td>
                </tr>
            </table>
            <a href="edit_profile.php" class="edit-button">Edit Profile</a>
        </div>
    </div>
</body>

</html>