<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/project_wad/styles/login_register/forgot_password.css">
</head>

<body>

    <div class="container">
        <h1>Forgot Password</h1> <!--form data will be sent using the HTTP POST method.-->
        <form method="post" action="/project_wad/backend/forgot_password.php">
            <label for='email'>Enter your Email:</label>
            <input type='email' name="email" id="email"></input>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>

</html>