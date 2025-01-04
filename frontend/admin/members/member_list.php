<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Initialize $members variable
$members = [];

// Fetch registered users (exclude admin users)
$query = "SELECT fullName AS name, contactNumber AS phone_number, email, CONCAT(address, ', ', postcode, ', ', city, ', ', state) AS full_address, user_id FROM users WHERE role = 'registeredUser'";
$result = $conn->query($query);

// Check if the query succeeded
if ($result && $result->num_rows > 0) {
    // Fetch all member records as an associative array
    $members = $result->fetch_all(MYSQLI_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/project_wad/styles/admin/member_list.css">
</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/admin_header.php'; ?>
    <div class="greetings">
        <h1>Dashboard / Member list</h1>
        <h2>Member List</h2>
    </div>
    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/admin/dashboard/admin_dashboard.php">Dashboard</a>
                <a href="/project_wad/frontend/admin/members/member_list.php">Member List</a>
                <a href="#">Appointment</a>
                <a href="/project_wad/frontend/admin/payment/payment_list.php">Payment List</a>
                <a href="/project_wad/frontend/admin/services/services.php">Services</a>
                <a href="/project_wad/frontend/admin/activities/manage_activities.php">Activities</a>
                <a href="/project_wad/frontend/admin/doctors/manage_doctors.php">Doctors</a>
                <a href="/project_wad/frontend/admin/promotions/manage_promotions.php">Promotions</a>
                <a href="logout.php">Log Out</a>
            </nav>
        </aside>

        <section class="member-list">
            <h1>All Members</h1>
            <div class="search-container">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search members by name, email, city..." />
            </div>
            <table id="memberTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($members) > 0): ?>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['name']); ?></td>
                                <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['full_address']); ?></td>
                                <td>
                                    <a href="/project_wad/frontend/admin/members/view_profile.php?user_id=<?php echo $member['user_id']; ?>">
                                        <button class="view-profile-btn">View Full Profile</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No members found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>

    <script src="/project_wad/javascript/member_list.js"></script>

</body>

</html>
