<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | EcoQuest</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/auth/login.css">
    <link rel="stylesheet" href="../styles/auth/register.css">

    <script src="../scripts/script.js"></script>
    <script src="../scripts/auth/register.js"></script>
</head>

<body>
    <?php
        session_start();
        $conn = new mysqli("localhost", "root", "", "ecoquest");

        $duplicateUsername = false;

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST["password"];
            $confirm_password = $_POST["confirm_password"];

            if(empty($username)){
                echo "Please enter valid username";
            }
            elseif(empty($email)){
                echo "Please enter valid email";
            }
            elseif(empty($password)){
                echo "Please enter valid password";
            }
            elseif(empty($confirm_password)){
                echo "Please confirm your password";
            }
            elseif($password != $confirm_password){
                echo "Passwords do not match";
            }
            else{
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO USERS (username, email, password) VALUES ('$username', '$email', '$hash')";

                    try {
                        mysqli_query($conn, $sql);
                        $_SESSION['message'] = "Account created successfully!";
                        header("Location: login.php");
                        exit();
                    }
                    catch(mysqli_sql_exception $e) {
                        if($e->getCode() == 1062){
                            echo "<script>duplicateUsername = true</script>";
                        } 
                        else {
                            echo "Error: " . $e->getMessage();
                        }
                    }

                }
            }

        mysqli_close($conn);
    ?>

    <div id="parent">
        <div id="logo">
            <h1>EcoQuest</h1>
        </div>

        <div id="login-page-box">
            <div id="greeting">
                <h3>Welcome</h3>
                <h4 id="subtitle">Sign up to begin your journey</h4>
            </div>



            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="login">
                <div id="input-section">    
                    <h4>Username</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="user-icon" src="/assets/user.svg" alt="user-icon">
                        </div>
                        <input type="text" name="username" id="username" placeholder="Enter your username"> 
                    </div>
                    <span id="username-error" class="error-message"></span>
                    <span id="duplicate-username-error" class="error-message"></span>

                    <h4>Email</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="email-icon" src="/assets/email.svg" alt="email-icon">
                        </div>
                        <input type="email" name="email" id="email" placeholder="Enter your email"> 
                    </div>
                    <span id="email-error" class="error-message"></span>

                    <h4>Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input id="password" type="password" name="password" placeholder="Enter your password">
                    </div>
                    <span id="password-error" class="error-message"></span>

                    <h4>Confirm Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input id="confirm-password" type="password" name="confirm_password" placeholder="Confirm your password">
                    </div>
                    <span id="confirm-password-error" class="error-message"></span>
                </div>
    
                <div>
                    <input type="submit" name="submit" value="Create account" class="button" id="login-button" onclick="return validateForm()">
                </div>
            </form>

            <div id="no-account">
                <h3>Already have an account?<a href="/auth/login.php">Sign In</a></h3>
            </div>
        </div>
    </div>
</body>

</html>