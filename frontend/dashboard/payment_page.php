<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include '../../backend/db_connect.php'; // Include database connection

// Fetch user's appointments for the dropdown
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT appointment_id, date, time FROM appointments WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$appointments = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="../../styles/payment.css">
</head>

<body>
    <h1>Make a Payment</h1>

    <!-- Display Success or Error Messages -->
    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Payment successful!</p>
    <?php elseif (isset($_GET['error'])): ?>
        <p style="color: red;">Error processing payment. Please try again.</p>
    <?php endif; ?>

    <form action="../../backend/process_payment.php" method="POST">
        <label for="appointment_id">Select Appointment:</label>
        <select id="appointment_id" name="appointment_id" required>
            <?php foreach ($appointments as $appointment): ?>
                <option value="<?php echo $appointment['appointment_id']; ?>">
                    Appointment on <?php echo htmlspecialchars($appointment['date']); ?> at <?php echo htmlspecialchars($appointment['time']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="amount">Payment Amount (RM):</label>
        <input type="number" id="amount" name="amount" step="0.01" min="0" required>
        <br><br>

        <button type="submit">Pay Now</button>
    </form>
</body>

</html>
