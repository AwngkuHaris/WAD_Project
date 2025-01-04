<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project_wad/frontend/login_register/welcomepage.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id']) && isset($_POST['cart_id'])) {
    $user_id = $_SESSION['user_id'];
    $service_id = intval($_POST['service_id']);
    $cart_id = intval($_POST['cart_id']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $problem_description = $_POST['problem_description'];

    // Convert the 12-hour time format to 24-hour format
    $appointment_time_24 = date("H:i", strtotime($appointment_time));

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert into appointments
        $insert_appointment_query = "
        INSERT INTO appointments 
        (user_id, name, email, phone, address, date, time, problem, status, created_at, quantity, service_id, service_name, amount) 
        SELECT 
            u.user_id, 
            u.fullName, 
            u.email, 
            u.contactNumber,
            CONCAT(u.address, ', ', u.postcode, ', ', u.city, ', ', u.state) AS full_address,
            ?, ?, ?, 'pending', NOW(), 
            c.quantity, 
            s.service_id, 
            s.service_name, 
            ((s.price * c.quantity) * 1.08) AS total_amount -- Include 8% tax
        FROM cart c
        JOIN services s ON c.service_id = s.service_id
        JOIN users u ON u.user_id = c.user_id
        WHERE c.cart_id = ? AND c.user_id = ?
        ";

        $stmt = $conn->prepare($insert_appointment_query);
        $stmt->bind_param(
            "sssii",
            $appointment_date,
            $appointment_time_24,
            $problem_description,
            $cart_id,
            $user_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to insert into appointments.");
        }

        $appointment_id = $stmt->insert_id; // Get the ID of the newly created appointment

        // Fetch the relevant details for payments
        $fetch_payment_data_query = "
            SELECT 
                s.service_name, 
                s.price AS service_price, 
                c.quantity, 
                ((s.price * c.quantity) * 1.08) AS total_amount
            FROM cart c
            JOIN services s ON c.service_id = s.service_id
            WHERE c.cart_id = ?
        ";
        $stmt_payment_data = $conn->prepare($fetch_payment_data_query);
        $stmt_payment_data->bind_param("i", $cart_id);
        $stmt_payment_data->execute();
        $result = $stmt_payment_data->get_result();
        $payment_data = $result->fetch_assoc();
        $stmt_payment_data->close();

        // Insert corresponding payment record
        $insert_payment_query = "
            INSERT INTO payments 
            (appointment_id, user_id, amount, status, service_name, service_price, quantity)
            VALUES (?, ?, ?, 'pending', ?, ?, ?)
        ";
        $stmt_payment = $conn->prepare($insert_payment_query);
        $stmt_payment->bind_param(
            "iidssd",
            $appointment_id,
            $user_id,
            $payment_data['total_amount'],
            $payment_data['service_name'],
            $payment_data['service_price'],
            $payment_data['quantity']
        );

        if (!$stmt_payment->execute()) {
            throw new Exception("Failed to insert into payments.");
        }
        $stmt_payment->close();

        // Remove the cart item
        $delete_cart_query = "DELETE FROM cart WHERE cart_id = ?";
        $stmt_delete = $conn->prepare($delete_cart_query);
        $stmt_delete->bind_param("i", $cart_id);
        if (!$stmt_delete->execute()) {
            throw new Exception("Failed to remove cart item.");
        }
        $stmt_delete->close();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Appointment booked successfully!'); window.location.href = '/project_wad/frontend/dashboard/dashboard.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "<script>alert('Failed to book the appointment. Error: " . $e->getMessage() . "'); window.location.href = '/project_wad/frontend/dashboard/book_appointment.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
}

$conn->close();
?>
