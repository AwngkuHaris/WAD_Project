<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (isset($_GET['cart_id']) && !empty($_GET['cart_id'])) {
    $cart_id = intval($_GET['cart_id']); // Sanitize cart_id input

    // Check if user is registered or unregistered
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_identifier = isset($_SESSION['user_identifier']) ? $_SESSION['user_identifier'] : null;

    if ($user_id || $user_identifier) {
        // Prepare the DELETE query
        $delete_query = "
            DELETE FROM cart 
            WHERE cart_id = ? 
              AND (user_id = ? OR user_identifier = ?)
        ";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("iis", $cart_id, $user_id, $user_identifier);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo "<script>alert('Item successfully removed from cart.'); window.location.href = '/project_wad/frontend/cart/publicUser_cart.php';</script>";
        } else {
            echo "<script>alert('Failed to delete item. Please try again.'); window.location.href = '/project_wad/frontend/cart/publicUser_cart.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('User session is invalid. Please try again.'); window.location.href = '/project_wad/frontend/cart/publicUser_cart.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. Cart item ID is missing.'); window.location.href = '/project_wad/frontend/cart/publicUser_cart.php';</script>";
}

$conn->close();
?>
