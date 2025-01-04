<?php
$password = 'admin!23'; // Replace with the password you want to hash.
$hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Generates a secure hashed password.

echo $hashedPassword; // Output the hashed password.
