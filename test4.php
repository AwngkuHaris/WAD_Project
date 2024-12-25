<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <p>You are logged in.</p>
    <button id="logoutButton">Logout</button>

    <script>
        document.getElementById("logoutButton").addEventListener("click", async function () {
            try {
                const response = await fetch("backend/logout.php", { method: "POST" });
                if (response.ok) {
                    // Redirect to login page
                    window.location.href = "test2.php";
                } else {
                    alert("Logout failed. Please try again.");
                }
            } catch (error) {
                console.error("Error:", error);
                alert("An error occurred while logging out.");
            }
        });
    </script>
</body>
</html>
