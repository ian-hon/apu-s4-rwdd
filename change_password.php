<?php
include './api/conn.php'; // connects to the database

$query = 'SELECT * FROM ecoquest.USERS WHERE username = "user1"';
$result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
$user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Setting</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/change_password.css">
</head>

<body>
    <div id="parent">

        <div id="bg-color">

            <!-- navbar -->
            <div id="navbar">
                <a href="profile.html">
                    <img src="assets/ivp/arrow-back-basic-svgrepo-com (2).svg" alt="">
                </a>
            </div>

            <!-- Company name -->
            <div id="logoname">
                <h1>EcoQuest</h1>
                <p>Change Your Password</p>
                <p>Enter a new password below to change your <br>password</p>
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

            <!-- Update password -->
            <div id="updatePassword">
                <div id="update-pass">
                    <p style="color: grey;">Confirm Changes?</p>
                    <button>
                        <a href="">
                            Update Password
                        </a>
                    </button>
                </div>
            </div>


        </div>
    </div>
</body>

</html>