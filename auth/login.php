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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $username = $_POST["username"];
        $password = $_POST["password"];

        if(empty($username)){
            echo "Please enter a username";
        }
        elseif(empty($password)){
            echo "Please enter a password";
        }
        else{
            if (isset($username) && isset($password)) {
                $result = $conn->query("SELECT * FROM USERS WHERE username = '{$username}' LIMIT 1");

                $hash = password_hash($password, PASSWORD_DEFAULT);

                if ($result->num_rows >= 1) {
                    // username exists
                    $record = $result->fetch_assoc();
                    if ($hash == $record['password']) {
                        echo "password matches";
                    } else {
                        echo "password no match";
                    }
                } else {
                    // username doesnt exist
                    echo "username no exist";
                }
            }        
        }
    }
    ?>

    <div id="parent">
        <div id="logo">
            <h1>EcoQuest</h1>
        </div>

        <div id="login-page-box">
            <div id="greeting">
                <h3>Welcome Back</h3>
                <h4 id="subtitle">Sign in to continue your journey</h4>
            </div>


            <form action="<?php ($_SERVER["PHP_SELF"])?>" method="post" name="login">
                <div id="input-section"> 

                    <h4>Username</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="user-icon" src="/assets/user.svg" alt="user-icon">
                        </div>
                        <input type="text" name="username" placeholder="Enter your username"> 
                    </div>

                    <h4>Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input type="password" name="password" placeholder="Enter your password">
                    </div>
                </div>


                <div id="remember-me-forgot-password">
                    <div id="remember-me">
                        <div>
                            <input id="remember-me-text" type="checkbox">
                        </div>                  
                        <div>
                            <label for="remember-me-text" id="remember-me-text">Remember me</label>
                        </div>  
                    </div>
                    <div id="forgot-password">
                        <a href="/auth/password-recovery.html" target="_blank">Forgot Password?</a>
                    </div>
                </div>


                <div>
                    <input type="submit" name="submit" value="Login" class="button" id="login-button">
                </div>
            </form>

            <div id="no-account">
                <h3>Don't have an account?<a href="/auth/register.html">Sign Up</a></h3>
            </div>
        </div>
    </div>

    <script src="../scripts/script.js"></script>
</body>
</html>

<?php
    mysqli_close($conn);
?>