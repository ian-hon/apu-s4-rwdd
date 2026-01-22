<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | EcoQuest</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/auth/login.css">
    <link rel="stylesheet" href="../styles/auth/register.css">
</head>

<body>
    <?php
        $conn = new mysqli("localhost", "root", "", "ecoquest");

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST["password"];

            if(empty($username)){
                echo "Please enter a username";
            }
            elseif(empty($password)){
                echo "Please enter a password";
            }
            else{
                if (isset($username) && isset($password)) {

                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $sql = "INSERT INTO USERS (username, password) VALUES ('$username', '$hash')";

                    try {
                        mysqli_query($conn, $sql);
                        echo "Account created successfully!";
                    }
                    catch(mysqli_sql_exception $e) {
                        if($e->getCode() == 1062){
                            echo "Username already exists. Please choose a different username.";
                        } 
                        else {
                            echo "Error: " . $e->getMessage();
                        }
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

            <form action="" method="post" name="login">
                <div id="input-section">    
                    <h4>Email</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="email-icon" src="/assets/email.svg" alt="email-icon">
                        </div>
                        <input type="email" placeholder="Enter your email"> 
                    </div>
                    <h4>Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input type="password" placeholder="Enter your password">
                    </div>
                    <h4>Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input type="password" placeholder="Enter your password">
                    </div>
                </div>
            </form>
            
            <div>
                <button class="button" id="login-button">Create Account</button>
            </div>
            <div id="no-account">
                <h3>Already have an account?<a href="/auth/login.html">Sign In</a></h3>
            </div>
        </div>
    </div>

    <script src="../scripts/script.js"></script>
</body>

</html>