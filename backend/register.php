<?php
include('db_connect.php'); // Assume $conn is initialized here

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect and sanitize inputs
    $fullName = htmlspecialchars($_POST['fullName'] ?? '', ENT_QUOTES, 'UTF-8');
    $myKadNumber = $_POST['myKadNumber'] ?? '';
    $dateOfBirth = $_POST['dateOfBirth'] ?? '';
    $contactNumber = $_POST['contactNumber'] ?? '';
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($_POST['city'] ?? '', ENT_QUOTES, 'UTF-8');
    $postcode = $_POST['postcode'] ?? '';
    $state = htmlspecialchars($_POST['state'] ?? '', ENT_QUOTES, 'UTF-8'); // New field
    $country = $_POST['country'] ?? '';

    // Validate required fields
    if (!$fullName || !$email || !$password || !$gender || !$address || !$city || !$postcode || !$state || !$country) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
        exit;
    }

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'This email is already registered.']);
        $checkStmt->close();
        exit;
    }

    $checkStmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert the new user
    $stmt = $conn->prepare("
        INSERT INTO users (fullName, myKadNumber, dateOfBirth, contactNumber, email, password, gender, address, city, postcode, state, country)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssssssss",
        $fullName,
        $myKadNumber,
        $dateOfBirth,
        $contactNumber,
        $email,
        $hashedPassword,
        $gender,
        $address,
        $city,
        $postcode,
        $state,
        $country
    );

    if ($stmt->execute()) {
        // Redirect to the login page after successful registration
        header("Location: project_wad/frontend/login_register/memberlogin.html");
        exit(); // Ensure no further code is executed
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again.']);
    }

    $stmt->close();
    $conn->close();
}
