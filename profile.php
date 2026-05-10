<?php
include './api/conn.php'; // connects to the database
include './api/credentials.php';
include_once './api/users/functions.php';

include './api/points/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    $maxSize = 5 * 1024 * 1024; // 5MB

    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize && $file['error'] === 0) {
        $imageData = file_get_contents($file['tmp_name']);
        $imageData = mysqli_real_escape_string($dbConnection, $imageData);

        $updateQuery = "UPDATE users SET profile_picture = '$imageData' WHERE username = '$username'";
        try {
            if (mysqli_query($dbConnection, $updateQuery)) {
                header("Location: profile.php");
                exit();
            }
        } catch (Exception $e) {
            header("Location: profile.php");
            // var_dump($e);
        }
    }
}

$user = user_fetch($username);
$totalPoints = points_get_total($username);
$currentPoints = points_get_current($username);


include './api/users/contribution.php';
include './api/goals/functions.php';

$contribution = user_get_contribution_total_worded($username);

$impactMessageGenerator = function ($contribution) {
    $plasticKg = $contribution['plastic']['total'];
    $electricKwh = $contribution['electric']['total'];
    $carbonKg = $contribution['carbon']['total'];
    $trashKg = $contribution['trash']['total'];

    $bottles = number_format(round($plasticKg * 50)); // 1 kg plastic = 50 bottles
    $energy = number_format(round($electricKwh * 10)); // 1 kWh = 10 hours of 100W bulb
    $barrels = number_format(round($carbonKg / 317)); // 1 barrel crude oil = 317 kg CO2
    $trees = number_format(round($carbonKg / 25)); // 1 tree absorbs 25 kg CO2/year
    $trashBags = number_format(round($trashKg / 10)); // 1 trash bag = 10 kg waste

    $message = [
        "You saved <span style='color: #519C03;'>{$bottles} bottles</span> this year! That requires <span style='color: #519C03;'>{$energy}kWh</span> of energy to process, or <span style='color: #519C03;'>{$barrels} barrels</span> of crude oil.",

        "You offset <span style='color: #519C03;'>{$carbonKg}kg of CO₂</span> this year! That's equal to planting <span style='color: #519C03;'>{$trees} trees</span> for an entire year.",

        "You conserved <span style='color: #519C03;'>{$electricKwh}kWh</span> of electricity! That prevented <span style='color: #519C03;'>{$carbonKg}kg of CO₂</span> emissions from power plants.",

        "You collected <span style='color: #519C03;'>{$trashKg}kg of trash</span> this year! That's <span style='color: #519C03;'>{$trashBags} garbage bags</span> kept out of our environment."
    ];
    return $message[array_rand($message)];
}

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

            <!-- navbar -->
            <div id="top-navbar">
                <a href="dashboard.php">
                    <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                </a>
                <p style="font-weight: bold; font-size: 20px;">Profile</p>
                <button id="hamburger">
                    <img src="./assets/burger.svg" alt="">
                </button>
            </div>
            <hr style="color: #222;">

            <div id="element">

                <!-- profile -->
                <div id="profile">
                    <div id="pfp">
                        <?php if ($user['profile_picture']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="assets/ivp/profile-picture.avif" alt="Default Profile Picture">
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data" id="pfp-form">
                            <label for="pfp-upload" id="pfp-label">
                                <img src="assets/pen_white.svg" alt="Edit">
                            </label>
                            <input type="file" name="profile_picture" id="pfp-upload" accept="image/*">
                        </form>
                    </div>
                    <div class="user">
                        <p>Username</p>
                        <a href="#">
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
                        <p><?php echo $totalPoints ?></p>
                    </div>

                    <div class="point">
                        <img src="assets/ivp/leaf-svgrepo-com.svg" alt="">
                        <p>Available</p>
                        <p><?php echo $currentPoints ?></p>
                    </div>
                </div>

                <!-- Impact -->
                <div id="impact">
                    <p class="impact-title">Your Impact This Year</p>
                    <div id="impact-info">
                        <p><?php echo $impactMessageGenerator($contribution);  ?></p>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/leaf-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p><?php echo $contribution['carbon']['term'] ?></p>
                            <p><?php echo $contribution['carbon']['total'] ?> <?php echo $contribution['carbon']['unit'] ?></p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/thunder-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p><?php echo $contribution['electric']['term'] ?></p>
                            <p><?php echo $contribution['electric']['total'] ?> <?php echo $contribution['electric']['unit'] ?></p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/recycled-plastic-bag-svgrepo-com (1).svg" alt="">
                        <div class="impact-name">
                            <p><?php echo $contribution['plastic']['term'] ?></p>
                            <p><?php echo $contribution['plastic']['total'] ?> <?php echo $contribution['plastic']['unit'] ?></p>
                        </div>
                    </div>
                    <div class="impact-list">
                        <img src="assets/ivp/trash-list-svgrepo-com.svg" alt="">
                        <div class="impact-name">
                            <p><?php echo $contribution['trash']['term'] ?></p>
                            <p><?php echo $contribution['trash']['total'] ?> <?php echo $contribution['trash']['unit'] ?></p>
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
                        <a href="auth/login.php">
                            <img src="assets/ivp/logout-svgrepo-com.svg" alt="">
                            Logout
                        </a>

                    </button>
                </div>
            </div>

        </div>
        <?php include './components/navbar.php'; ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/profile.js" defer></script>
</body>

</html>