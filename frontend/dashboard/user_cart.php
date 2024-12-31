<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

// Fetch cart details for the logged-in user (assuming user_id is stored in session)
session_start();
$user_id = $_SESSION['user_id'];

$query = "

SELECT 
        c.cart_id,
        s.service_id, 
        s.service_name, 
        c.quantity, 
        MAX(a.date) AS appointment_date, 
        s.price, 
        ((s.price * c.quantity) * 1.08) AS total_price 
    FROM cart c
    JOIN services s ON c.service_id = s.service_id
    LEFT JOIN appointments a ON c.service_id = a.service_id AND c.user_id = a.user_id
    WHERE c.user_id = ? AND c.status = 'unpaid'
    GROUP BY c.cart_id, c.service_id, s.service_name, c.quantity, s.price
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Service List</title>
    <link rel="stylesheet" href="/project_wad/styles/registeredUser/user_cart.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>
    <div class="greetings">
        <h1>Dashboard</h1>
        <h2>Welcome Back <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="menu">
                <a href="/project_wad/frontend/dashboard/dashboard.php">Dashboard</a>
                <a href="#">Profile</a>
                <a href="/project_wad/frontend/dashboard/user_services.php">Services</a>
                <a href="/project_wad/frontend/dashboard/book_appointment.php">Appointment</a>
                <a href="/project_wad/frontend/dashboard/payment_page.php">Payments</a>
                <a href="/project_wad/frontend/dashboard/user_cart.php">Cart</a>
                <a href="/project_wad/backend/logout.php">Log Out</a>
            </nav>
        </aside>
        <div class="cart-container">
            <h1>My Cart</h1>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Quantity</th>
                        <th>Appointment Date</th>
                        <th>Price (per person)</th>
                        <th>Total Price</th>
                        <th>Actions</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($cart_items) > 0): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($item['appointment_date']))); ?></td>
                                <td>RM<?php echo number_format($item['price'], 2); ?></td>
                                <td>RM<?php echo number_format($item['total_price'], 2); ?></td>
                                <td>
                                    <!-- Make Payment button -->
                                    <form method="POST" action="/project_wad/backend/make_payment.php" style="display: inline;">
                                        <input type="hidden" name="service_id" value="<?php echo $item['service_id']; ?>">
                                        <button type="submit" class="action-btn pay-btn">Make Payment</button>
                                    </form>
                                    <!-- Delete button -->
                                    <form method="POST" action="/project_wad/backend/delete_cart_item.php" style="display: inline;">
                                        <input type="hidden" name="cart_item_id" value="<?php echo htmlspecialchars($item['cart_id']); ?>">
                                        <button type="submit" class="action-btn delete-btn">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: bold;">Total:</td>
                        <td colspan="2" style="font-weight: bold;">
                            RM<?php echo number_format(array_sum(array_column($cart_items, 'total_price')), 2); ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</body>

</html>