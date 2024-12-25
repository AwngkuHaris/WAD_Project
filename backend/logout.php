<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Return a successful response
http_response_code(200);
echo json_encode(["message" => "Logged out successfully"]);
?>
