<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EcoQuest</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/auth/login.css">
</head>

<body>
    <?php
    // by default, $username and $password will be null.

    // what happens here is that the user clicks "Login", then it will redirect back to this same page
    // but this time, the $username and $password variables will be set.

    // then we can check if the username exists and if the password is correct

    // if username exists
    //      if password is correct
    //          store username and password inside browser's storage
    //          redirect to the dashboard
    //      else:
    //          (password incorrect)
    //          display "incorrect login credentials"
    // else:
    //      (username doesnt exist)
    //      display "incorrect login credentials"

    $conn = new mysqli("localhost", "root", "", "ecoquest");

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (isset($username) && isset($password)) {
        $result = $conn->query("SELECT * FROM USERS WHERE username = '{$username}' LIMIT 1");
        if ($result->num_rows >= 1) {
            // username exists
            $record = $result->fetch_assoc();
            if ($password == $record['password']) {
                echo "password matches";
            } else {
                echo "password no match";
            }
        } else {
            // username doesnt exist
            echo "username no exist";
        }
    }


    ?>

    <div id="parent">
        <div id="logo">
            <h1>EcoQuest</h1>
        </div>

        <!-- once "Login" is pressed, it will redirect back to this same page -->
        <form id="login-page-box" method="POST">
            <div id="welcome-back">
                <h3>Welcome Back</h3>
                <h4 id="subtitle">Sign in to continue your journey</h4>
            </div>
            <div id="input-section">
                <h3>Email</h3>
                <input name="username" type="text" placeholder="your@email.com">
            </div>
            <div id="input-section">
                <h3>Password</h3>
                <input name="password" type="password" placeholder="Enter your password">
            </div>
            <div id="forgot-password">
                <a href="https://www.wikipedia.com/" target="_blank">Forgot Password?</a>
            </div>
            <div>
                <h5 id="or-page-divider">or</h5>
            </div>
            <div id="continue-with">
                <button class="button">Continue with Google</button>
                <button class="button">Continue with Apple</button>
            </div>
            <div>
                <input class="button" id="login-button" type="submit" value="Login">
                <!-- <button class="button" id="login-button">Login</button> -->
            </div>
            <div id="no-account">
                <h4>Don't have an account?<a href="/auth/register.html">Sign Up</a></h4>
            </div>
        </form>
    </div>

    <script src="../scripts/script.js"></script>
</body>

</html>