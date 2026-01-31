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
    session_start();

    $redirects = array(
        'user' => '../dashboard.php',
        'admin' => '../admin.php',
        'curator' => '../curator.php',
    );

    $conn = new mysqli("localhost", "root", "", "ecoquest");

    // isset check for existing extended session, sets to true or false, prevents errors on first login
    // secondary check for if extended session is true, then set session lifetime
    if (isset($_SESSION['extended_session']) && $_SESSION['extended_session']) {
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
        session_set_cookie_params(['lifetime' => 30 * 24 * 60 * 60]);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST["username"];
        $password = $_POST["password"];
        $remember_me = isset($_POST["remember_me"]) ? true : false;

        if (empty($username)) {
            echo "Please enter a username";
        } elseif (empty($password)) {
            echo "Please enter a password";
        } else {
            $result = $conn->query("SELECT * FROM USERS WHERE username = '" . $conn->real_escape_string($username) . "' LIMIT 1");

            if ($result->num_rows >= 1) {
                // username exists
                $record = $result->fetch_assoc();
                if (password_verify($password, $record['password'])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $record['password'];

                    // if click remember me, extend session lifetime
                    if ($remember_me) {
                        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
                        session_set_cookie_params(['lifetime' => 30 * 24 * 60 * 60]);
                        $_SESSION['extended_session'] = true;
                    }

                    header("Location: {$redirects[$record['role']]}");
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect login credentials";
                }
            } else {
                // username no exist
                $_SESSION['error'] = "Incorrect login credentials";
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
                <h3>Welcome Back</h3>
                <h4 id="subtitle">Sign in to continue your journey</h4>
            </div>

            <?php
            if (isset($_SESSION['message'])) {
                echo "<p class='success-message'>" . htmlspecialchars($_SESSION['message']) . "</p>";
                unset($_SESSION['message']);
            }
            if (isset($_SESSION['error'])) {
                echo "<p class='error-message-box'>" . htmlspecialchars($_SESSION['error']) . "</p>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="login">
                <div id="input-section">
                    <h4>Username</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="user-icon" src="/assets/user.svg" alt="user-icon">
                        </div>
                        <input type="text" name="username" id="username" placeholder="Enter your username">
                    </div>
                    <h5><span id="username-error" class="error-message"></span></h5>

                    <h4>Password</h4>
                    <div id="input-form">
                        <div id="input-icon">
                            <img id="password-icon" src="/assets/password.svg" alt="password-icon">
                        </div>
                        <input id="password" type="password" name="password" placeholder="Enter your password">
                    </div>
                    <h5><span id="password-error" class="error-message"></span></h5>
                </div>


                <div id="remember-me-forgot-password">
                    <div id="remember-me">
                        <div>
                            <input id="remember-me-text" type="checkbox" name="remember_me" value="1">
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
                    <input type="submit" value="Login" class="button" id="login-button"
                        onclick="return validateForm()">
                </div>
            </form>

            <div id="no-account">
                <h3>Don't have an account?<a href="/auth/register.php">Sign Up</a></h3>
            </div>
        </div>
    </div>

    <script src="../scripts/script.js"></script>
    <script src="../scripts/auth/login.js"></script>
</body>

</html>