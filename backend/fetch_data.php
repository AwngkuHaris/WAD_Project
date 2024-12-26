<?php
include('backend/db_connect.php');  // Include the connection file

$sql = "SELECT * FROM users";  // Replace with your actual table name
$result = $conn->query($sql);

// Check if there are any rows
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["user_id"]. " - Name: " . $row["fullName"]. " - Email: " . $row["email"]. "<br>";  // Modify according to your table columns
    }
} else {
    echo "0 results";
}

$conn->close();
?>
