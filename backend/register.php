<?php
include('db_connect.php'); // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullName = $_POST['fullName'] ?? null;
    $myKadNumber = $_POST['myKadNumber'] ?? null;
    $dateOfBirth = $_POST['dateOfBirth'] ?? null;
    $contactNumber = $_POST['contactNumber'] ?? null;
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $address = $_POST['address'] ?? null;
    $city = $_POST['city'] ?? null;
    $postcode = $_POST['postcode'] ?? null;
    $country = $_POST['country'] ?? null;

    // Check if required fields are filled
    if (!$fullName || !$myKadNumber || !$dateOfBirth || !$contactNumber || !$email || !$password || !$gender || !$address || !$city || !$postcode || !$country) {
        die("Please fill in all the required fields.");
    }

    // Validate MyKad number
    if (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $myKadNumber)) {
        die("Invalid MyKad number.");
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[$&+,:;=?@#|\'<>.^*()%!-])[A-Za-z\d$&+,:;=?@#|\'<>.^*()%!-]{6,8}$/', $password)) {
        die("Password does not meet criteria.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Store user in database
    $conn = new mysqli('localhost', 'root', '', 'anmas_samarahan');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (fullName, myKadNumber, dateOfBirth, contactNumber, email, password, gender, address, city, postcode, country) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $fullName, $myKadNumber, $dateOfBirth, $contactNumber, $email, $hashedPassword, $gender, $address, $city, $postcode, $country);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


