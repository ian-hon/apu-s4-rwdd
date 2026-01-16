<?php
include './api/conn.php'; // connects to the database

$query = 'SELECT * FROM ecoquest.USERS WHERE username = "user1"';
$result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
$user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

// refer line 50

// total point
$totalPoint = "SELECT sum(task.reward_rate * submission.action_count) AS total_points FROM submission 
            INNER JOIN task ON submission.task_ID = task.ID WHERE submission.user = 'user1'";
$totalPointResult = mysqli_query($dbConnection, $totalPoint);
$allPoint = mysqli_fetch_assoc($totalPointResult)['total_points'];

// available point 
$availablePoint = "SELECT $allPoint - sum(redemption.price) AS available_point FROM redemption WHERE redemption.user = 'user1'";
$availableResult = mysqli_query($dbConnection, $availablePoint);
$allAvailable = mysqli_fetch_assoc($availableResult);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | EcoQuest</title>
    <link rel="stylesheet" href="./styles/profile.css">
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
    <div id="parent">
        <div id="bg-color">

            <div id="navbar">
                <a href="dashboard.html">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
                <p style="font-weight: bold; font-size: 20px;">Profile</p>
                <a href="">
                    <img src="./assets/burger.svg" alt="">
                </a>
            </div>
            <hr style="color: #222;">

            <div id="element">

                <!-- profile -->
                <div id="profile">
                    <div id="pfp">
                        <img src="assets/ivp/profile-picture.avif" alt="">
                    </div>
                    <div class="user">
                        <p>Username</p>
                        <a href="#name">
                            <img src="assets/ivp/pen-svgrepo-com.svg" alt="">
                        </a>
                        <input type="text" id="name" value="<?php echo $user['name'] ?>" required readonly>
                    </div>
                    <div class="user">
                        <p>Password</p>
                        <a href="change_password.php">
                            <img src="assets/ivp/pen-svgrepo-com.svg" alt="">
                        </a>
                        <input type="password" id="password" value="eco1234" required readonly>
                    </div>
                </div>

                <!-- Point-tracking -->
                <div id="point-tracker">
                    <div class="point">
                        <img src="assets/ivp/medal-champion-award-winner-olympic-23-svgrepo-com.svg" alt="">
                        <p>Total Point</p>
                        <p><?php echo $allPoint ?></p>
                    </div>

                    <div class="point">
                        <img src="assets/ivp/leaf-svgrepo-com.svg" alt="">
                        <p>Available</p>
                        <p><?php echo $allAvailable['available_point'] ?></p>
                    </div>
                </div>

                <!-- Impact -->
                <div id="impact">
                    <p class="impact-title">Your Impact This Year</p>
                    <div id="impact-info">
                        <p>You saved <span style="color: #519C03;">5,000 bottles</span> this year! That
                            requires <span style="color: #519C03;">50kWh</span> of energy to process, or <span
                                style="color: #519C03;">5
                                barrels</span> of crude oil
                        </p>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/leaf-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p>Carbon Reduced</p>
                            <p>2.5 tons CO₂</p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/thunder-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p>Energy Saved</p>
                            <p>50 kWh</p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                        <div class="impact-name">
                            <p>Water Saved</p>
                            <p>1,500 liters</p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/gas-station-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p>Oil Saved</p>
                            <p>5 barrels</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Info -->
                <div id="personal-info">
                    <div id="member">
                        <p>Member Since</p>
                        <p>January 2024</p>
                    </div>
                </div>

                <!-- Logout -->
                <div id="logout">
                    <button>
                        <a href="auth/login.html">
                            <img src="assets/ivp/logout-svgrepo-com.svg" alt="">
                            Logout
                        </a>

                    </button>
                </div>
            </div>

        </div>
    </div>

    <script src="./scripts/script.js"></script>
</body>

</html>