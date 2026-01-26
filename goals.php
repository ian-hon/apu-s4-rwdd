<?php
include './api/conn.php'; // connects to the database

$query = 'SELECT * FROM ecoquest.USERS WHERE username = "user1"';
$result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
$user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

$pTitle = "SELECT * FROM goals WHERE type = 'personal'";
$titleResult = mysqli_query($dbConnection, $pTitle);

include './api/users/contribution.php';
include './api/goals/functions.php';

$impact = user_get_contribution_total('user1');
// e.g. $impact = [1 => 50, 2 => 20]

$contribution = user_get_contribution_total_worded('user1');
// e.g. $goal_type_id = 1 (plastic), $data = ['term' => 'plastic saved', 'total' => 50, 'unit' => 'kg', 'decimals' => 1]
// You can use this data to display user's contribution in different goal types

$actionDone = user_get_actions_total('user1');
// e.g. returns integer 156 representing total actions done by the user

$streak = user_get_streak('user1');
// e.g. returns integer 12 representing current streak count

$personalProgress = goals_contributions_all('user1', type: 'personal');
$globalProgress = goals_contributions_all('user1', type: 'global');

$consistency = goals_overall_completion_rate();

$onTrackP = goals_all_completed('user1', type: 'personal');
$allGoalsP = goals_contributions_all('user1', type: 'personal');

$onTrackG = goals_all_completed('user1', type: 'global');
$allGoalsG = goals_contributions_all('user1', type: 'global');

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
                    <a href="./dashboard.php">
                        <img src="./assets/ivp/arrow-back-basic-svgrepo-com.svg" alt="">
                    </a>
                </button>
                <h3>Goal Tracking</h3>

                <button id="hamburger">
                    <img src="./assets/burger.svg" alt="">
                </button>
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
                    <p class="p3"><?php echo $contribution['carbon']['unit'] ?> of <?php echo $contribution['carbon']['term'] ?></p>
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
                <?php
                function generateImpactDescription($goalTypeId, $totalValue)
                {
                    switch ($goalTypeId) {
                        case 'carbon':
                            return "Your carbon offset equals the CO₂ absorption of " . floor($totalValue / 16) . " trees/year";
                        case 'plastic':
                            return "Equal to " . ($totalValue * 50) . " plastic bottles kept out of landfills";
                        case 'electric':
                            return "This can keep a 100-watt light bulb on for  " . floor($totalValue * 10) . " hours";
                        case 'trash':
                            return "That is equivalent to " . floor($totalValue / 5) . "kg of waste diverted from landfills";
                        default:
                            return "";
                    }
                }

                $descriptionCarbon = generateImpactDescription('carbon', $contribution['carbon']['total']);
                $descriptionPlastic = generateImpactDescription('plastic', $contribution['plastic']['total']);
                $descriptionElectric = generateImpactDescription('electric', $contribution['electric']['total']);
                $descriptionTrash = generateImpactDescription('trash', $contribution['trash']['total']);
                ?>

                <div class="impt">
                    <img src="./assets/ivp/car-svgrepo-com.svg" alt="">
                    <!-- <img src=<?php echo $contribution['carbon']['media'] ?>> -->
                    <p class="impt-p1"><?php echo $contribution['carbon']['term'] ?></p>
                    <p class="impt-p2"><?php echo $contribution['carbon']['total'] ?> <?php echo $contribution['carbon']['unit'] ?></p>
                    <p class="impt-p3"><?php echo $descriptionCarbon ?></p>
                </div>
                <div class="impt">
                    <img src="./assets/ivp/bottle-plastic-recycle-recycling-svgrepo-com.svg">
                    <p class="impt-p1"><?php echo $contribution['plastic']['term'] ?></p>
                    <p class="impt-p2"><?php echo $contribution['plastic']['total'] ?> <?php echo $contribution['plastic']['unit'] ?></p>
                    <p class="impt-p3"><?php echo $descriptionPlastic ?></p>
                </div>
                <div class="impt">
                    <img src="./assets/ivp/electric-electricity-svgrepo-com.svg">
                    <p class="impt-p1"><?php echo $contribution['electric']['term'] ?></p>
                    <p class="impt-p2"><?php echo $contribution['electric']['total'] ?> <?php echo $contribution['electric']['unit'] ?></p>
                    <p class="impt-p3"><?php echo $descriptionElectric ?></p>
                </div>
                <div class="impt">
                    <img src="./assets/ivp/trash-svgrepo-com.svg">
                    <p class="impt-p1"><?php echo $contribution['trash']['term'] ?></p>
                    <p class="impt-p2"><?php echo $contribution['trash']['total'] ?> <?php echo $contribution['trash']['unit'] ?></p>
                    <p class="impt-p3"><?php echo $descriptionTrash ?></p>
                </div>
            </div>

            <!-- Goal Tracking -->
            <div id="tracking">
                <button class="Personal">Personal</button>
                <button class="Global">Global</button>
            </div>

            <!-- Goal -->
            <div id="goal">
                <div class="personal-goals">
                    <?php
                    foreach ($personalProgress as $row) { ?>
                        <div class="goals">
                            <p class="goals-p1"><?php echo $row['title']; ?></p>
                            <p class="goals-p2"><?php echo $row['total']; ?>/<span><?php echo $row['goal']; ?></span></p>
                            <div class="icons">
                                <img src="./assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                            </div>

                            <?php $progress = floor(($row['total'] / $row['goal']) * 100); ?>

                            <div class="progress" style="background: conic-gradient(var(--accent) calc(<?php echo ($progress) ?>%), var(--tertiary) 0) !important;">
                                <div id="thumb">
                                    <p class="percent"><?php echo $progress ?> %</p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="global-goals">
                    <?php
                    foreach ($globalProgress as $row) { ?>
                        <div class="goals">
                            <p class="goals-p1"><?php echo $row['title']; ?></p>
                            <p class="goals-p2"><?php echo $row['total']; ?>/<span><?php echo $row['goal']; ?></span></p>
                            <div class="icons">
                                <img src="./assets/ivp/water-drops-svgrepo-com (1).svg" alt="">
                            </div>

                            <?php $progress = floor(($row['total'] / $row['goal']) * 100); ?>

                            <div class="progress" style="background: conic-gradient(var(--accent) calc(<?php echo ($progress) ?>%), var(--tertiary) 0) !important;">
                                <div id="thumb">
                                    <p class="percent"><?php echo $progress ?> %</p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- track info -->
            <div id="track-info">
                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/calender-svgrepo-com.svg" alt="">
                        <p class="info-p1">TIME LEFT</p>
                    </div>

                    <p class="info-p2" id="countdown-timer">
                        <?php
                        $now = time(); //curent time in seconds (unix timestamp)
                        $nextSunday = strtotime('next Sunday 00:00:00'); // next Sunday at midnight in seconds
                        $timeLeft = $nextSunday - $now;

                        $days = floor($timeLeft / (60 * 60 * 24)); //86400
                        $hours = floor(($timeLeft % (60 * 60 * 24)) / (60 * 60)); //3600
                        $minutes = floor(($timeLeft % (60 * 60)) / 60);

                        echo $days . "d " . $hours . "h " . $minutes . "m ";
                        ?>
                    </p>

                    <p class="info-p3">to complete this weekly</p>
                </div>

                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/target-marketing-goal-svgrepo-com.svg" alt="">
                        <p class="info-p1">ON TRACK</p>
                    </div>

                    <P class="info-p2-personal"><?php echo count($onTrackP) ?> of <?php echo count($allGoalsP); ?></P>
                    <P class="info-p2-global"><?php echo count($onTrackG) ?> of <?php echo count($allGoalsG); ?></P>
                    <!-- its array a list of item, can't display directly -->
                    <p class="info-p3">goals</p>
                </div>

                <div class="info">
                    <div class="card-header">
                        <img src="./assets/ivp/up-trend-svgrepo-com.svg" alt="">
                        <p class="info-p1">CONSISTENCY</p>
                    </div>

                    <P class="info-p2"><?php echo $consistency ?>%</P>
                    <p class="info-p3">above average</p>
                </div>
            </div>
        </div>
    </div>

    <?php include './components/navbar.php' ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/goals.js" defer></script>
</body>

</html>