<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';
session_start();

// Check if user is registered or unregistered
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_identifier = isset($_SESSION['user_identifier']) ? $_SESSION['user_identifier'] : null;

// Fetch cart items based on user_id or user_identifier with status = 'unpaid'
$query = "
    SELECT 
        c.cart_id, 
        s.service_name, 
        c.quantity, 
        s.price 
    FROM cart c
    JOIN services s ON c.service_id = s.service_id
    WHERE (c.user_id = ? OR c.user_identifier = ?)
      AND c.status = 'unpaid'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $user_identifier);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);

// Calculate total price including 8% tax
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += ($item['price'] * $item['quantity']) * 1.08;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Anmas Dental Specialist Clinic - Public User Cart</title>
    <link rel="stylesheet" href="/project_wad/styles/publicUser_cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/header.php'; ?>
    <section class="breadcrumb">
        <div class="breadcrumb-container">
            <p><a href="/index.php">Home</a> / Cart</p>
            <h1>My Cart</h1>
        </div>
    </section>
    <main>
        <div class="cart-container">
            <h1>My Cart</h1>
            <table>
                <thead>
                    <tr>
                        <th>Services</th>
                        <th>Quantity</th>
                        <th>Price (per person)</th>
                        <th>Amount (with 8% tax)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cart_items)): ?>
                        <?php foreach ($cart_items as $item): ?>
                            <?php
                            $amount = ($item['price'] * $item['quantity']) * 1.08; // Calculate amount with 8% tax
                            ?>
                            <tr>
                                <td data-price="<?php echo $item['price']; ?>">
                                    <?php echo htmlspecialchars($item['service_name']); ?>
                                </td>
                                <td>
                                    <span class="qty-display"><?php echo $item['quantity']; ?></span>
                                </td>
                                <td>RM<?php echo number_format($item['price'], 2); ?></td>
                                <td>RM<?php echo number_format($amount, 2); ?></td>
                                <td>
                                    <!-- Book Appointment Button -->
                                    <button
                                        class="book-btn"
                                        onclick="handleAppointment(<?php echo $item['cart_id']; ?>, '<?php echo $user_id; ?>')">
                                        Book Appointment
                                    </button>

                                    <!-- Delete Button with Trash Icon -->
                                    <button
                                        class="delete-btn"
                                        onclick="deleteCartItem(<?php echo $item['cart_id']; ?>)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="total-row">
                <span class="total-label">Total:</span>
                <span id="total-price">RM<?php echo number_format($total_price, 2); ?></span>
            </div>
        </div>
    </main>

    <script>
        function handleAppointment(cartId, userId) {
            if (userId) {
                // Redirect registered users to the payment page
                window.location.href = `/project_wad/frontend/payment_page.php?cart_id=${cartId}`;
            } else {
                // Show overlay and membership message for unregistered users
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('membership-message').style.display = 'block';
            }
        }


        function deleteCartItem(cartId) {
            console.log(`Deleting cart item with ID: ${cartId}`); // Debugging
            if (confirm('Are you sure you want to delete this service from your cart?')) {
                window.location.href = `/project_wad/backend/delete_cart.php?cart_id=${cartId}`;
            }
        }

        function closeOverlay() {
            document.getElementById('overlay').style.display = 'none';
            document.getElementById('membership-message').style.display = 'none';
        }
    </script>

    <div class="overlay" id="overlay"></div>
    <section class="membership-message" id="membership-message">
        <h2>Oops...</h2>
        <p>You need to be a member to book an appointment.</p>
        <a href="/project_wad/frontend/login_register/membersignup.html" class="membership-link">
            <button>Become a Member</button>
        </a>
        <button class="cancel-btn" onclick="closeOverlay()">Cancel</button>
    </section>




    <?php include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/footer.php'; ?>
</body>

</html>