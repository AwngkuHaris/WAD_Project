<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (!isset($_GET['token'])) {
    die("Invalid reset token.");
}

$token = $_GET['token'];

// Verify the token and check expiration
$query = "SELECT * FROM users WHERE reset_token = ? AND token_expiration > NOW()";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Invalid or expired token.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="/project_wad/styles/login_register/forgot_password.css">
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="/project_wad/backend/reset_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>

</html>