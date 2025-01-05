<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['token']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Verify the token
    $query = "SELECT * FROM users WHERE reset_token = ? AND token_expiration > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Invalid or expired token.");
    }

    // Hash the new password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Update the user's password and clear the reset token
    $update_query = "UPDATE users SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ss", $hashed_password, $token);
    $update_stmt->execute();

    echo "Password reset successfully. You can now log in.";
} else {
    echo "Invalid request.";
}
?>
