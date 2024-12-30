<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include '../../backend/db_connect.php';

// Fetch service details based on the service_id passed in the URL
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

$query = "SELECT service_name, price FROM services WHERE service_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    echo "Service not found.";
    exit();
}

// Handle appointment booking
// After successful appointment booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_quantity = $_POST['appointment_quantity']; // Get the quantity

    // Book the appointment
    $insert_query = "
        INSERT INTO appointments (user_id, service_id, date, time, quantity, status) 
        VALUES (?, ?, ?, ?, ?, 'Pending')
    ";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("iissi", $user_id, $service_id, $appointment_date, $appointment_time, $appointment_quantity);

    if ($stmt->execute()) {
        // Update the cart after successful booking
        $cart_query = "
            INSERT INTO cart (user_id, service_id, quantity) 
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE 
            quantity = quantity + VALUES(quantity)
        ";
        $cart_stmt = $conn->prepare($cart_query);
        $cart_stmt->bind_param("iii", $user_id, $service_id, $appointment_quantity);

        if ($cart_stmt->execute()) {
            echo "<script>alert('Appointment booked and cart updated successfully!'); window.location.href = 'user_services.php';</script>";
        } else {
            echo "Appointment booked, but failed to update the cart.";
        }
        $cart_stmt->close();
    } else {
        echo "Error booking appointment.";
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
    <title>Book Appointment</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/book_appointment.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>

    <div class="booking-container">
        <h1>Book Appointment</h1>
        <h2>Service: <?php echo htmlspecialchars($service['service_name']); ?></h2>
        <p>Price: RM<?php echo number_format($service['price'], 2); ?></p>

        <form method="POST" action="">
            <label for="appointment_date">Select Date:</label>
            <input
                type="date"
                id="appointment_date"
                name="appointment_date"
                min="<?php echo date('Y-m-d'); ?>"
                required>

            <label for="appointment_time">Select Time:</label>
            <select id="appointment_time" name="appointment_time" required>
                <option value="08:00">08:00</option>
                <option value="08:30">08:30</option>
                <option value="09:00">09:00</option>
                <option value="09:30">09:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
                <option value="16:30">16:30</option>
                <option value="17:00">17:00</option>
                <option value="17:30">17:30</option>
            </select>

            <label for="appointment_quantity">Select Quantity:</label>
            <select id="appointment_quantity" name="appointment_quantity" required>
                <option value="1">1 Person</option>
                <option value="2">2 People</option>
                <option value="3">3 People</option>
            </select>

            <button type="submit" class="book-btn">Book Appointment</button>
        </form>
    </div>
</body>

</html>