<?php
include('db_connect.php'); // Include database connection

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password']; // Password provided by the user

    // Check if the email exists in the database
    $check_email_sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email exists, fetch the user's data
        $user = $result->fetch_assoc();
        
        // Verify if the provided password matches the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Password is correct, login successful

            // Store user information in the session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            echo json_encode(["success" => true, "message" => "Login successful!"]);
        } else {
            // Incorrect password
            echo json_encode(["success" => false, "message" => "Incorrect password. Please try again."]);
        }
    } else {
        // Email does not exist
        echo json_encode(["success" => false, "message" => "No account found with that email."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
