<?php
session_start();
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, fullName, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fullName, $hashedPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $fullName;
            $_SESSION['user_role'] = $role;

            // Transfer cart items if user_identifier exists
            if (isset($_SESSION['user_identifier'])) {
                $user_identifier = $_SESSION['user_identifier'];

                // Transfer cart items from user_identifier to user_id
                $update_cart_query = "
                    UPDATE cart 
                    SET user_id = ?, user_identifier = NULL 
                    WHERE user_identifier = ?
                ";
                $update_stmt = $conn->prepare($update_cart_query);
                $update_stmt->bind_param("is", $id, $user_identifier);
                $update_stmt->execute();
                $update_stmt->close();

                // Unset user_identifier from the session
                unset($_SESSION['user_identifier']);
            } else {
                // Ensure user_identifier is unset even if no cart items exist
                unset($_SESSION['user_identifier']);
            }

            // Redirect based on role
            if ($role === 'registeredUser') {
                header("Location: ../frontend/dashboard/dashboard.php");
            } else {
                header("Location: ../frontend/admin/dashboard/admin_dashboard.php");
            }
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with this email.";
    }

    $stmt->close();
    $conn->close();
}
