<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="styles/test4.css">
</head>

<body>
    <h1>Welcome to the Dashboard</h1>
    <p>You are logged in.</p>
    <button id="logoutButton">Logout</button>

    <script>
        document.getElementById("logoutButton").addEventListener("click", async function() {
            try {
                const response = await fetch("backend/logout.php", {
                    method: "POST"
                });
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

    <div class="row">
        <div class="root">
            <div class="wrapper">
                <div class="text">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Totam saepe expedita aspernatur omnis quia.
                </div>
            </div>
            <img class="button" src="images/teeth_bridge.png" alt="Click Me" />
        </div>
        <div class="root">
            <div class="wrapper">
                <div class="text">
                    Another item content goes here.
                </div>
            </div>
            <img class="button" src="images/teeth_inlay.png" alt="Click Me" />
        </div>
        <!-- Add more items as needed -->
    </div>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

    <script src="javascript/test4.js"></script>


</body>

</html>