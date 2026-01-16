<?php
include './api/conn.php'; // connects to the database

$query = 'SELECT * FROM ecoquest.USERS WHERE username = "user1"';
$result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
$user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

$pTitle = "SELECT * FROM goals WHERE type = 'personal'";
$titleResult = mysqli_query($dbConnection, $pTitle);

include './api/users/contribution.php';

$impact = user_get_contribution_total('user1');
// e.g. $impact = [1 => 50, 2 => 20]

$contribution = user_get_contribution_total_worded('user1');
// e.g. $goal_type_id = 1 (plastic), $data = ['term' => 'plastic saved', 'total' => 50, 'unit' => 'kg', 'decimals' => 1]
// You can use this data to display user's contribution in different goal types

$actionDone = user_get_actions_total('user1');
// e.g. returns integer 156 representing total actions done by the user

$streak = user_get_streak('user1');
// e.g. returns integer 12 representing current streak count
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goals | EcoQuest</title>
    <link rel="stylesheet" href="./styles/goals.css">
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
    <div id="bg-color">
        <!-- Header -->
        <div id="header">
            <div class="top">
                <button id="back">
                    <a href="./dashboard.html">
                        <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                    </a>
                    <<<<<<< Updated upstream=======</button>
                        <h3>Goal Tracking</h3>

                        <button id="hamburger">
                            <img src="./assets/burger.svg" alt="">
                            >>>>>>> Stashed changes
                        </button>
                        <h3>Goal Tracking</h3>

                        <button id="hamburger">
                            <img src="./assets/burger.svg" alt="">
                        </button>
                        <!-- <?php include './components/navbar.php'; ?> -->
            </div>
            <hr style="color: #101309; margin: 0;">
        </div>

        <div id="elements">
            <!-- Statistic -->
            <div id="statistic">
                <div class="ststc">
                    <img src="./assets/ivp/water-drops-svgrepo-com (1).svg">
                    <p class="p1"><?php echo $contribution['plastic']['term'] ?></p>
                    <p class="p2"><?php echo $contribution['plastic']['total'] ?></p>
                    <p class="p3"><?php echo $contribution['plastic']['unit'] ?> of <?php echo $contribution['plastic']['term'] ?></p>
                </div>

                <div class="ststc">
                    <img src="./assets/ivp/leaf-svgrepo-com.svg">
                    <p class="p1"><?php echo $contribution['carbon']['term'] ?></p>
                    <p class="p2"><?php echo $contribution['carbon']['total'] ?></p>
                    <p class="p3">tons of CO₂</p>
                </div>

                <div class="ststc">
                    <img src="./assets/ivp/bolt-thunder-svgrepo-com.svg">
                    <p class="p1">ACTIONS DONE</p>
                    <p class="p2"><?php echo $actionDone ?></p>
                    <p class="p3">eco activities</p>
                </div>

                <div class="ststc">
                    <img src="./assets/ivp/fire-svgrepo-com (1).svg">
                    <p class="p1">CURRENT STREAK</p>
                    <p class="p2"><?php echo $streak ?></p>
                    <p class="p3">days strong</p>
                </div>
            </div>

            <!-- Impact -->
            <div id="impact-header">
                <img id="your-impact" src="./assets/ivp/light-bulb-svgrepo-com.svg">
                <h4 id="header-impact">Your impact</h4>
            </div>
            <div id="impact">
                <div class="impt">
                    <img src="./assets/ivp/car-svgrepo-com.svg">
                    <p class="impt-p1">CO₂ Saved</p>
                    <p class="impt-p2">5.2 tons</p>
                    <p class="impt-p3">That's equivalent to driving from Thailand to Korea and back!</p>
                </div>
                <div class="impt">
                    <img src="./assets/ivp/bottle-plastic-recycle-recycling-svgrepo-com.svg">
                    <p class="impt-p1">Plastic Diverted</p>
                    <p class="impt-p2">62.5 kg</p>
                    <p class="impt-p3">Equal to 2,500 plastic bottels kept out of landfills</p>
                </div>
                <div class="impt">
                    <img src="./assets/ivp/tree-svgrepo-com.svg">
                    <p class="impt-p1">Tree Impact</p>
                    <p class="impt-p2">~30 Trees</p>
                    <p class="impt-p3">Your carbon offset equal the CO₂ absorption of 30 trees/year</p>
                </div>
            </div>
            <!-- get on contribution total -->

            <!-- Goal Tracking -->
            <div id="tracking">
                <button class="Personal">Personal</button>
                <button class="Global">Global</button>
                <!-- curator.js -->
                <!-- dashboard.css -->
                <!-- both inside curator folder -->
            </div>

            <!-- Goal -->
            <div id="goal">
                <?php
                while ($allTitle = mysqli_fetch_assoc($titleResult)) { ?>
                    <div class="goals">
                        <p class="goals-p1"><?php echo $allTitle['title']; ?></p>
                        <p class="goals-p2"><span><?php echo $allTitle['decimal_points']; ?></span>/<?php echo $allTitle['goal']; ?></p>
                        <div class="icons">
                            <img src="./assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                        </div>
                        <div class="progress">
                            <div id="thumb">
                                <p class="percent">64%</p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <!-- track info -->
            <div id="track-info">
                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/calender-svgrepo-com.svg" alt="">
                        <p class="info-p1">TIME LEFT</p>
                    </div>

                    <P class="info-p2"><?php echo $allTitle['ending_time']; ?></P>
                    <p class="info-p3">to complete this weekly</p>
                </div>

                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/target-marketing-goal-svgrepo-com.svg" alt="">
                        <p class="info-p1">ON TRACK</p>
                    </div>

                    <P class="info-p2">2 of 2</P>
                    <p class="info-p3">goals</p>
                </div>

                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/up-trend-svgrepo-com.svg" alt="">
                        <p class="info-p1">CONSISTENCY</p>
                    </div>

                    <P class="info-p2">92%</P>
                    <p class="info-p3">above average</p>
                </div>
            </div>
        </div>
    </div>

    <?php include './components/navbar.php' ?>
    </div>

    <script src="./scripts/script.js"></script>
    <<<<<<< Updated upstream
        <script src="./scripts/navbar.js">
        </script>
        <script src="./scripts/goals.js"></script>
        =======
        <script src="./scripts/navbar.js" defer></script>
        <script src="./scripts/goals.js" defer></script>
        >>>>>>> Stashed changes
</body>

</html>