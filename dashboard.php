<?php
include './api/users/contribution.php';
include './api/task/functions.php';
include './api/points/functions.php';

include './api/credentials.php';
include './api/users/functions.php';

enforce_role('user');

$tasks = task_fetch_daily_tasks();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta name="viewport" content="initial-scale=1, maximum-scale=1"> -->

    <title>Dashboard | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/dashboard.css">
</head>

<body>
    <div id="page">
        <div id="parent">
            <div id="header">
                <div id="stats">
                    <div id="leaves">
                        <h4>
                            <?php echo points_get_current($username); // TODO: REPLACE WITH SESSION STORAGE 
                            ?>
                        </h4>
                        <img src="./assets/leaf.svg">
                    </div>
                    <div id="streak">
                        <img src="./assets/fire.svg">
                        <h4>
                            <?php echo user_get_streak($username) ?>
                        </h4>
                    </div>
                </div>
                <img onclick="toggleNavbar()" id="burger" src="./assets/burger.svg">
            </div>
            <div id="streak-info" class="border">
                <h6 class="box-title">CURRENT STREAK</h6>
                <img src="./assets/fire.svg" class="border">
                <div id="details">
                    <h2>
                        <?php echo user_get_streak($username) . " day" . (user_get_streak($username) == 1 ? "" : "s") ?>
                    </h2>
                    <h5>
                        better than <?php echo user_get_streak_percentile($username) // TODO: REPLACE WITH SESSION STORAGE 
                                    ?>% of users!
                    </h5>
                </div>
            </div>
            <div id="fun-fact" class="border" data-state='loading' data-payload='<?php echo htmlspecialchars(json_encode(user_get_contribution_total_worded($username))); ?>'>
                <div id="data">
                    <h4>Fun fact!</h4>
                    <h5></h5>
                    <!-- <h5>You saved 10kg of plastic and 5kWh of electricity. That is equivalent to 500 lego bricks and 3 bags of coal!</h5> -->
                </div>
                <div id="loading">
                    <div id="spinner"></div>
                    <h5>generating fun fact with AI...</h5>
                </div>
                <div id="failure">
                    <h4>oh no</h4>
                    <h5>something went wrong trying to generate. maybe try again later?</h5>
                </div>
            </div>
            <div id="tasks">
                <div id="title">
                    <h3>DAILY TASKS</h3>
                    <a href="./tasks.php" id="view-all" class="border">
                        <h5>view all</h5>
                        <img src="./assets/arrow_right.svg">
                    </a>
                </div>
                <div id="task-container">
                    <?php foreach (
                        array_filter($tasks, function ($v, $_) {
                            global $username;
                            return !task_already_submitted($v['ID'], $username); // TODO: REPLACE WITH SESSION
                        }, ARRAY_FILTER_USE_BOTH) as $row
                    ): ?>
                        <a class="task-card" id="<?php echo $row['occurance_type'] ?>" href="./view_tasks.php?ID=<?php echo urlencode($row['ID']) ?>">
                            <img id="icon" class="border" src="./assets/leaf.svg">
                            <div id="details">
                                <h4><?php echo $row['title'] ?></h4>
                                <h6><?php echo $row['description'] ?></h6>
                            </div>
                            <div id="points">
                                <h6><?php echo $row['target'] * $row['reward_rate'] ?></h6>
                                <img src="./assets/leaf.svg">
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <div id="finished-all">
                        <h5>wow, you completed all your daily tasks!</h5>
                        <h6 class="countdown-finished"></h6>
                    </div>
                </div>
                <div id="timer">
                    <h6>new tasks in</h6>
                    <h6 class="countdown"></h6>
                </div>
            </div>
            <div id="goals">
                <h3 id="title">GLOBAL GOALS</h3>
                <div id="goal-container">
                    <div class="goal-card">
                        <div id="header">
                            <img src="./assets/plastic.svg" class="border">
                            <div id="details">
                                <h4>
                                    Plastic saved
                                </h4>
                                <h5>
                                    5,440 contributors
                                </h5>
                            </div>
                        </div>
                        <div id="data">
                            <div>
                                <div id="details">
                                    <h2 id="target">
                                        40.5
                                    </h2>
                                    <h5>/ 50 kg</h5>
                                </div>
                                <h6>83% progress</h6>
                            </div>
                            <div class="progressbar">
                                <div id="thumb"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="goal-container">
                    <div class="goal-card">
                        <div id="header">
                            <img src="./assets/cloud.svg" class="border">

                            <div id="details">
                                <h4>
                                    CO<sub>2</sub> offset
                                </h4>
                                <h5>
                                    1,553 contributors
                                </h5>
                            </div>
                        </div>
                        <div id="data">
                            <div>
                                <div id="details">
                                    <h2 id="target">
                                        25.3
                                    </h2>
                                    <h5>/ 30 tonnes</h5>
                                </div>
                                <h6>83% progress</h6>
                            </div>
                            <div class="progressbar">
                                <div id="thumb"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include './components/navbar.php' ?>
    </div>
    <script src="./scripts/script.js"></script>
    <script src="./scripts/cohere.js"></script>
    <script src="./scripts/navbar.js" defer></script>
    <script src="./scripts/dashboard.js"></script>
</body>

</html>