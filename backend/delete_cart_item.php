<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

if (isset($_POST['cart_item_id']) && !empty($_POST['cart_item_id'])) {
    $cart_id = intval($_POST['cart_item_id']);

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete associated appointment
        $delete_appointment_query = "
            DELETE FROM appointments 
            WHERE user_id = ? 
              AND service_id = (
                  SELECT service_id 
                  FROM cart 
                  WHERE cart_id = ?
              )
        ";
        $stmt = $conn->prepare($delete_appointment_query);
        $stmt->bind_param("ii", $_SESSION['user_id'], $cart_id);
        $stmt->execute();

        // Delete the cart item
        $delete_cart_query = "DELETE FROM cart WHERE cart_id = ?";
        $stmt = $conn->prepare($delete_cart_query);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        echo "<script>alert('Item successfully removed from cart.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "<script>alert('Failed to delete item. Please try again.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request. Cart item ID is missing.'); window.location.href = '/project_wad/frontend/dashboard/user_cart.php';</script>";
}
?>
