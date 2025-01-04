<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/frontend/login_register/welcomepage.html");
    exit();
}

// Ensure `service_id` is received from the form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'])) {
    $user_id = $_SESSION['user_id'];
    $service_id = intval($_POST['service_id']);

    // Fetch cart details for the selected service_id
    $cart_query = "
        SELECT c.cart_id, c.quantity, s.service_name 
        FROM cart c
        JOIN services s ON c.service_id = s.service_id
        WHERE c.user_id = ? AND c.service_id = ? AND c.status = 'unpaid'
    ";
    $stmt_cart = $conn->prepare($cart_query);
    $stmt_cart->bind_param("ii", $user_id, $service_id);
    $stmt_cart->execute();
    $cart_result = $stmt_cart->get_result();
    $cart_item = $cart_result->fetch_assoc();

    if ($cart_item) {
        // Display appointment form with pre-filled details
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
            <div class="container">
                <h1>Book Appointment</h1>
                <form action="/project_wad/backend/process_appointment.php" method="POST">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" value="<?php echo htmlspecialchars($cart_item['service_name']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" value="<?php echo htmlspecialchars($cart_item['quantity']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="date" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="appointment_time">Appointment Time</label>
                        <select id="appointment_time" name="appointment_time" required>
                            <option value="">Select a time</option>
                            <option value="09:00 AM">09:00 AM</option>
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="11:00 AM">11:00 AM</option>
                            <option value="02:00 PM">02:00 PM</option>
                            <option value="03:00 PM">03:00 PM</option>
                            <option value="04:00 PM">04:00 PM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="problem_description">Describe your problem</label>
                        <textarea id="problem_description" name="problem_description" placeholder="Describe your problem"></textarea>
                    </div>
                    <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                    <input type="hidden" name="cart_id" value="<?php echo $cart_item['cart_id']; ?>">
                    <button type="submit">Submit</button>
                </form>
            </div>
        </body>

        </html>
<?php
    } else {
        echo "<script>alert('No cart item found for this service.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
    }
    $stmt_cart->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
}
$conn->close();
?>