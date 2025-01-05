<?php
include $_SERVER['DOCUMENT_ROOT'] . '/project_wad/backend/db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . '/project_wad/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/project_wad/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/project_wad/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);

    // Check if the email exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a unique reset token and expiration time
        $reset_token = bin2hex(random_bytes(32));
        $token_expiration = date("Y-m-d H:i:s", strtotime("+1 hour")); // Valid for 1 hour

        // Save token and expiration in the database
        $update_query = "UPDATE users SET reset_token = ?, token_expiration = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sss", $reset_token, $token_expiration, $email);
        $update_stmt->execute();

        // Send the reset email
        $reset_link = "https://yourdomain.com/project_wad/frontend/login_register/reset_password.php?token=$reset_token";

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Change to your SMTP host
            $mail->SMTPAuth = true;
            $mail->Username = 'hounkej@gmail.com'; // Change to your email
            $mail->Password = 'huih mmom igao ebes'; // Change to your email app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPDebug = 3; // Debug level: 3 provides detailed server communication
            $mail->Debugoutput = 'html'; // Output debugging messages in readable HTML format


            $mail->setFrom('your_email@gmail.com', 'Your Dental Place'); // Change to your email and business name
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "<p>We received a request to reset your password. Click the link below to reset it:</p>
                           <a href='$reset_link'>Reset Password</a><br><br>
                           If you did not request this, please ignore this email.";

            $mail->send();
            echo "A reset link has been sent to your email address.";
        } catch (Exception $e) {
            echo "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found.";
    }
} else {
    echo "Invalid request.";
}
