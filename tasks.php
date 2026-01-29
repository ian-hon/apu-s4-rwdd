<?php

include "./api/task/functions.php";
include "./api/users/contribution.php";
include "./api/credentials.php";
include "./api/users/functions.php";

$tasks = task_fetch_all_ongoing();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks | EcoQuest</title>
    <link rel="stylesheet" href="./styles/tasks.css">
    <link rel="stylesheet" href="./styles/style.css">
</head>

<body>
    <?php $headerTitle = "Tasks";
    include './components/header.php'; ?>

    <div id="cards">
        <div class="border">
            <span>
                <h2><?php echo user_get_streak($username) ?></h2>
                <img src="./assets/fire.svg">
            </span>
            <?php echo (user_check_already_submitted_today($username) ? "<h5>completed!</h5>" : "<h5 class='countdown'></h5>") ?>
        </div>
        <div class="border">
            <span>
                <h2 id=""><?php echo count(array_filter($tasks, function ($v, $_) {
                                global $username;
                                return ($v['occurance_type'] = 'daily') && task_already_submitted($v['ID'], $username);
                            }, ARRAY_FILTER_USE_BOTH)) ?></h2>
                <h4 id=""><?php echo "/" .  count(array_filter($tasks, function ($v, $_) {
                                return ($v['occurance_type'] = 'daily');
                            }, ARRAY_FILTER_USE_BOTH)) ?></h4>
            </span>
            <h5>daily tasks completed</h5>
        </div>
    </div>

    <div id="filters" data-filter='daily'>
        <h4 id="daily" class="border" onclick="toggleFilter('daily')">
            DAILY
        </h4>
        <h4 id="weekly" class="border" onclick="toggleFilter('weekly')">
            WEEKLY
        </h4>
    </div>

    <div id="container">
        <?php foreach (
            array_filter($tasks, function ($v, $_) {
                global $username;
                return !task_already_submitted($v['ID'], $username);
            }, ARRAY_FILTER_USE_BOTH) as $row
        ): ?>
            <a class="task-card" id="<?php echo $row['occurance_type'] ?>" href="./task_submission.php?ID=<?php echo urlencode($row['ID']) ?>">
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
        <div id="separator">
            <hr>
            <h5>already submitted</h5>
            <hr>
        </div>
        <?php foreach (
            array_filter($tasks, function ($v, $_) {
                global $username;
                return task_already_submitted($v['ID'], $username);
            }, ARRAY_FILTER_USE_BOTH) as $row
        ): ?>
            <div class="task-card" id="<?php echo $row['occurance_type'] ?>" data-state='submitted'>
                <img id="icon" class="border" src="./assets/leaf.svg">
                <div id="details">
                    <h4><?php echo $row['title'] ?></h4>
                    <h6><?php echo $row['description'] ?></h6>
                </div>
                <div id="points">
                    <h6><?php echo $row['target'] * $row['reward_rate'] ?></h6>
                    <img src="./assets/leaf.svg">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/tasks.js" defer></script>
</body>

</html>