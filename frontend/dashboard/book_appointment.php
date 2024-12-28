<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="../../styles/booking.css"> <!-- Add your CSS file -->
</head>

<body>
    <h1>Book an Appointment</h1>

    <!-- Display Success or Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Appointment booked successfully!</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">Error booking appointment. Please try again.</p>
    <?php endif; ?>

    <form action="../../backend/process_appointment.php" method="POST">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <br><br>

        <label for="time">Time:</label>
        <input type="time" id="time" name="time" required>
        <br><br>

        <label for="service_id">Service:</label>
        <select id="service_id" name="service_id" required>
            <option value="1">Cleaning</option>
            <option value="2">Extraction</option>
            <option value="3">Filling</option>
            <!-- Add more services as needed -->
        </select>
        <br><br>

        <button type="submit">Book Appointment</button>
    </form>
</body>

</html>
