<!DOCTYPE html>
<html lang="en">
<?php

include './api/credentials.php';
enforce_role('curator');

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curator Dashboard | EcoQuest</title>
    <link rel="stylesheet" href="./styles/curator/dashboard.css">
    <link rel="stylesheet" href="./styles/curator/sidebar.css">

    <link rel="stylesheet" href="./styles/curator/submissions.css">
    <link rel="stylesheet" href="./styles/curator/tasks.css">
    <link rel="stylesheet" href="./styles/curator/schedule.css">

    <link rel="stylesheet" href="./styles/style.css">

    <?php echo "<script>var username = '{$username}';</script>" ?>
</head>

<body>
    <div id="page">
        <div id="sidebar" data-filter="<?php echo isset($_GET['filter']) && (in_array($_GET['filter'], ['pending', 'approved', 'rejected'])) ? $_GET['filter'] : '' ?>" data-tab="">
            <div id="top">
                <h3>CURATOR DASHBOARD</h3>
            </div>
            <div id="tabs">
                <h5 id="submissions" class="border" onclick="changeTab('submissions')">SUBMISSIONS</h5>
                <h5 id="tasks" class="border" onclick="changeTab('tasks')">TASKS</h5>
                <h5 id="schedule" class="border" onclick="changeTab('schedule')">SCHEDULE</h5>
            </div>
            <hr>
            <div id="sidebar-content">
                <?php
                include './curator/sidebar/submissions.php';
                include './curator/sidebar/tasks.php';
                include './curator/sidebar/schedule.php';
                ?>
            </div>
        </div>
        <div id="content">
            <?php
            include './curator/submissions.php';
            include './curator/tasks.php';
            include './curator/schedule.php';
            ?>
        </div>
    </div>
    <script src="./scripts/utils.js"></script>
    <script src="./scripts/script.js"></script>
    <script src="./scripts/curator.js"></script>
    <div id="script-imports">
    </div>
    <script defer>
        <?php echo "changeTab('" .  (isset($_GET['tab']) && in_array($_GET['tab'], ["submissions", "tasks", "schedule"]) ? $_GET['tab'] : 'submissions') . "')" ?>
    </script>
</body>

</html>