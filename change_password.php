<?php
include './api/conn.php'; // connects to the database
include './api/users/functions.php';
include 'api/credentials.php';

// $query = 'SELECT * FROM ecoquest.USERS WHERE username = $username';
// $result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
// $user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user
$user = user_fetch($username);

// only run this code if the form is submitted
// $_SERVER = A giant information box that PHP automatically fills with details about the request
// REQUEST_METHOD = tells us whether the request is a GET or POST (Accesses a specific piece of information from the `$_SERVER` array)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPass = $_POST['currentPass'];
    $newPass = $_POST['newPass'];
    $confirmPass = $_POST['confirmPass'];

    $passwordMatches = false;

    // Check if password is hashed (starts with $2y$)
    if (strpos($user['password'], '$2y$') === 0) {
        // Hashed password - use password_verify
        $passwordMatches = password_verify($currentPass, $user['password']);
    } else {
        // Plain text password - use direct comparison
        $passwordMatches = ($currentPass === $user['password']);
    }

    // Check if current password matches
    if ($passwordMatches) {
        // Check if new password and confirm password match
        if ($newPass === $confirmPass) {
            //hashed the new pass before storing
            $hashedNewPass = password_hash($newPass, PASSWORD_DEFAULT);

            // Update the password in the database
            $updateQuery = "UPDATE ecoquest.USERS SET password = ? WHERE username = ?";
            $stmt = mysqli_prepare($dbConnection, $updateQuery); //mysqli_prepare in order to use '?' to prevent SQL injection
            mysqli_stmt_bind_param($stmt, 'ss', $hashedNewPass, $username);
            mysqli_stmt_execute($stmt);

            // Update session password
            $_SESSION['password'] = $hashedNewPass;

            echo "<script>alert('Password updated successfully!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('New password and confirm password do not match.');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Setting | EcoQuest</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/change_password.css">
</head>

<body>
    <div id="parent">

        <form action="#profile.php" method="post" id="bg-color">

            <!-- navbar -->
            <div id="top-navbar">
                <a href="profile.php">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
            </div>

            <!-- Company name -->
            <div id="logoname">
                <h1>EcoQuest</h1>
                <p>Change Your Password</p>
                <p>Enter a new password below to change your password</p>
            </div>

            <!-- Change password -->
            <div id="password">
                <div id="current-pass">
                    <h4>Current Password : <br></h4>
                    <input type="password" name="currentPass" id="currentPass" placeholder="Enter current password"
                        required>
                </div>
                <div id="new-pass">
                    <h4>New Password : <br></h4>
                    <input type="password" name="newPass" id="newPass" placeholder="Enter New Password" required>
                </div>
                <div id="confirm-pass">
                    <h4>Confirm Password : <br></h4>
                    <input type="password" name="confirmPass" id="confirmPass" placeholder="Confirm New Password"
                        required>
                </div>
            </div>

            <!-- Random Password Generator -->
            <button id="randomPass">Generate Random Password</button>

            <!-- Update password -->
            <div id="updatePassword">
                <div id="update-pass">
                    <button type="submit">
                        Update Password
                    </button>
                </div>
            </div>


        </form>
    </div>
    <script src="./scripts/change_password.js"></script>
</body>

</html>