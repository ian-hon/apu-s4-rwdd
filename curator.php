<!DOCTYPE html>
<html lang="en">

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
</head>

<body>
    <div id="page">
        <div id="sidebar" data-filter="" data-tab="schedule">
            <div id="top">
                <h3>CURATOR DASHBOARD</h3>
            </div>
            <div id="tabs">
                <h5 id="submissions" class="border" onclick="changeTab('submissions')">SUBMISSIONS</h5>
                <h5 id="tasks" class="border" onclick="changeTab('tasks')">TASKS</h5>
                <h5 id="schedule" class="border" onclick="changeTab('schedule')">SCHEDULE</h5>
            </div>
            <!-- <input> -->
            <hr>
            <div id="query">
                <?php
                include './curator/sidebar/query/submissions.php';
                include './curator/sidebar/query/tasks.php';
                include './curator/sidebar/query/schedule.php';
                ?>
            </div>
            <hr>
            <div id="overview">
                <h5>OVERVIEW</h5>
                <?php
                include './curator/sidebar/overview/submissions.php';
                include './curator/sidebar/overview/tasks.php';
                include './curator/sidebar/overview/schedule.php';
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
    <script src="./scripts/script.js"></script>
    <script src="./scripts/curator.js"></script>
    <script src="./scripts/curator/schedule.js"></script>
</body>

</html>