<?php
// Database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Initialize variables
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

if ($search_query) {
    // Search query
    $sql = "SELECT * FROM activities WHERE title LIKE ? OR description LIKE ? ORDER BY posted_date DESC";
    $stmt = $conn->prepare($sql);
    $like_query = '%' . $search_query . '%';
    $stmt->bind_param("ss", $like_query, $like_query);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Default query to fetch all activities
    $sql = "SELECT * FROM activities ORDER BY posted_date DESC";
    $result = $conn->query($sql);
}

// Fetch recent posts
$recent_sql = "SELECT activity_id, title FROM activities ORDER BY posted_date DESC LIMIT 5";
$recent_result = $conn->query($recent_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities</title>
    <link rel="stylesheet" href="/project_wad/styles/activities.css">
</head>
<body>
    <!-- Header Section -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

   <!-- Breadcrumb Section -->
   <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="index.php">Home</a> / Activities</p>
            <h1>Activities</h1>
        </div>
    </section>

    <div class="content">
        <!-- Blog Posts Section -->
        <div class="blog-posts">
            <?php if ($search_query): ?>
                <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
            <?php endif; ?>

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="post">
                        <!-- Post Thumbnail -->
                        <div class="post-thumbnail">
                        <img src="../../images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Post Details -->
                        <div class="post-details">
                            <div class="post-meta">Posted on <?php echo date('F j, Y', strtotime($row['posted_date'])); ?> by <?php echo htmlspecialchars($row['author']); ?></div>
                            <h2 class="post-title"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="post-excerpt"><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="view_activity.php?id=<?php echo $row['activity_id']; ?>" class="read-more">Read More</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No activities found<?php echo $search_query ? ' for your search query.' : '.'; ?></p>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Search Box -->
            <div class="search-box">
                <h3>Search</h3>
                <form action="activities.php" method="GET">
                    <input type="text" name="search" placeholder="Search activities..." required>
                    <button type="submit">Search</button>
                </form>
            </div>

            <!-- Recent Posts -->
            <div class="recent-posts">
                <h3>Recent Activities</h3>
                <ul>
                    <?php if ($recent_result && $recent_result->num_rows > 0): ?>
                        <?php while($recent = $recent_result->fetch_assoc()): ?>
                            <li>
                                <a href="view_activity.php?id=<?php echo $recent['id']; ?>">
                                    <?php echo htmlspecialchars($recent['title']); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No recent activities.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>

  <!-- Footer Section -->
  <?php include('../../footer.php'); ?>
</body>
</html>
