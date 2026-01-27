<?php
ob_start(); 
include 'api/reward/fetch_all.php'; 
include 'api/users/fetch_all.php'; 
include 'api/submission/fetch_all.php'; 
include 'api/task/fetch_all.php'; 
ob_end_clean(); 

// 1. Reward Stats
$totalResult['count'] = count($rewards);
$lowStockResult['count'] = 0;
$activeResult['count'] = 0;
$endedResult['count'] = 0;

$rawInitial = 0;
$rawRemaining = 0;

foreach ($rewards as $reward) {
    if ($reward['active']) { $activeResult['count']++; } 
    else { $endedResult['count']++; }
    
    if ($reward['remaining'] < 10) { $lowStockResult['count']++; }

    $rawInitial += (int)$reward['initial'];
    $rawRemaining += (int)$reward['remaining'];
}

// 2. Stock Circulation Math
$totalClaimed = $rawInitial - $rawRemaining;
$claimedPercentage['count'] = ($rawInitial > 0) ? round(($totalClaimed / $rawInitial) * 100, 1) : 0;

// 3. User Stats
$totalUser['count'] = 0;
foreach ($users as $user) {
    if ($user['role'] == 'user') { $totalUser['count']++; }
}

// 4. Submission/Success Stats
$totalSubCount = count($submissions);
$totalSubmission['count'] = $totalSubCount;
$completedCount = 0;

foreach ($submissions as $submission) {
    if ($submission['status'] == 'approved') { $completedCount++; }
}

$completedTask['count'] = $completedCount;
$successRate = ($totalSubCount > 0) ? round(($completedCount / $totalSubCount) * 100,1): 0;

$goalCounts = [
    'plastic'  => 0,
    'trash'    => 0,
    'electric' => 0,
    'carbon'   => 0
];

foreach ($submissions as $sub) {

    if ($sub['status'] === 'approved') {
        $taskId = $sub['task_ID'];
        
        if (isset($tasks[$taskId])) {
            $type = $tasks[$taskId]['goal_type'];
            if (array_key_exists($type, $goalCounts)) {
                $goalCounts[$type]++;
            }
        }
    }
}

$max = max($goalCounts);
$step4 = $max;                     
$step3 = round($max * 0.66);  
$step2 = round($max * 0.33);     
$step1 = round($max * 0);

if ($max <= 0) { $max = 1; }

$appCount = 0;
$penCount = 0;
$rejCount = 0;
$totalSub = count($submissions);

foreach ($submissions as $sub) {
    if ($sub['status'] === 'approved') $appCount++;
    elseif ($sub['status'] === 'pending') $penCount++;
    elseif ($sub['status'] === 'rejected') $rejCount++;
}

$pApp = ($totalSub > 0) ? ($appCount / $totalSub) * 100 : 0;
$pPen = ($totalSub > 0) ? ($penCount / $totalSub) * 100 : 0;
$pRej = ($totalSub > 0) ? ($rejCount / $totalSub) * 100 : 0;


$stop1 = $pApp;
$stop2 = $pApp + $pPen;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reward | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="./styles/admin/reward.css">
    <link rel="stylesheet" href="./styles/admin/create.css">
    <link rel="stylesheet" href="./styles/admin/stats.css">


</head>

<body>
    <div id="parent">
        <aside class="sidebar">
            <div id="top">
                <h3>Admin</h3>
                <h5>Reward Management</h5>
            </div>
            
            <div id="tabs">
                <button id="Reward" class="dash-btn active" onclick="changeTab('reward',event)">REWARD</button>
                <button id="Statistics" class="dash-btn" onclick="changeTab('stats',event)">STATISTICS</button>
            </div>

            <div id="overview">
                <h5>OVERVIEW</h5>
                <div id="reward-overview">
                    <div id="statistics">
                        <span id="total-reward" onclick="changeFilter('all', event)" style="cursor: pointer">
                            <h5>Total Reward</h5>
                            <img src="./assets/target.svg">
                            <h3><?= $totalResult['count'] ?? 0?></h3>
                        </span>
                        <span id="low-stock" onclick="changeFilter('low',event)" style="cursor: pointer">
                            <h5>Low Stock Alert</h5>
                            <img src="./assets/target.svg">
                            <h3><?= $lowStockResult['count'] ?? 0?></h3>
                            <h6>Require Attention</h6>
                        </span>
                    </div>
                    <div class="active-ended">
                        <span id="active" onclick="changeFilter('active', event)" style="cursor: pointer">
                            <img src="./assets/target.svg">
                            <h5>Active</h5>
                            <h3><?= $activeResult['count'] ?? 0?></h3>
                        </span>
                        <span id="ended" onclick="changeFilter('ended', event)" style="cursor: pointer">
                            <img src="./assets/target.svg">
                            <h5>Ended</h5>
                            <h3><?= $endedResult['count'] ?? 0 ?></h3>
                        </span>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <button class="logout-btn" onclick="window.location.href='logout.php'">
                    <div class="logout-content">
                        <img src="./assets/logout.png" alt="logout">
                        <span>Log Out</span>
                    </div>
                </button>
            </div>
        </aside>

        <main class="main">
            <section id="reward" class="page-content">
                <div id="header">
                    <div class="page-title">
                        <h3>Reward Management</h3>
                    </div>
                    <button class="conner-btn" onclick="openPopup()">+ Create Reward</button>
                </div>
                <div id="reward-container">
                    </div>
            </section>
            <section id="stats" class="page-content" style="display: none;">
                <div id="header">
                    <div class="page-title">
                        <h3>Statistics Dashboard</h3>
                    </div>
                <button class="conner-btn" onclick="reportPdf()">Export Report</button>
                    
                </div>
                    <div class="dashboard-container">
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Total Users</p>
                            <h2 class="value"><?= $totalUser['count'] ?? 0?></h2>
                            <p class="trend positive">Number of Eco-Warrior</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Tasks Completed</p>
                            <h2 class="value"><?= $completedTask['count'] ?? 0?></h2>
                            <p class="trend positive">Number of Task Completed</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Inventory Health</p>
                            <h2 class="value"><?= $claimedPercentage['count'] ??0?></h2>
                            <p class="trend negative">Percentage of Reward Claimed</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Success Rate</p>
                            <h2 class="value"><?=$successRate['count'] ?? 0?></h2>
                            <p class="trend positive">Percentage of User Tasks Verified</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
                </div>
                <nav class="filter-btn">
                    <button class="fil-btn active" onclick="filterTab('overview', event)">Overview</button>
                    <button class="fil-btn" onclick="filterTab('task', event)">Task Analytics</button>
                    <button class="fil-btn" onclick="filterTab('user', event)">User Analytics</button>
                </nav>
                <div id="chart-container">
                    <div id="filter-group" data-category="task">
                        <div class="chart">
                            <h3>Task Completion Trend</h3>
                            <div id="chart-wrapper">
                                <div class="y-axis">
                                    <span><?= $step4 ?></span>
                                    <span><?= $step3 ?></span>
                                    <span><?= $step2 ?></span>
                                    <span><?= $step1 ?></span>
                                </div>
                                <div class="bar-chart">
                                    <?php foreach ($goalCounts as $label => $val): ?>
                                        <?php $percent = ($max > 0) ? ($val / $max) * 100 : 0; ?>
                                        <div class="bar"
                                            style="--value: <?= $percent ?>"
                                            data-value="<?= $val ?> Tasks">
                                            <span><?= ucfirst($label)?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>                                   

                    <div id="filter-group" data-category="user">
                        <div class="chart">
                            <h3>Task Status Distribution</h3>
                            <div id="pie-wrapper">
                                <div class="pie-chart"
                                    style="background: conic-gradient(
                                       var(--accent) 0% <?= $stop1 ?>%,
                                       var(--pending) <?= $stop1 ?>% <?= $stop2 ?>%,
                                       var(--error) <?= $stop2 ?>% 100%
                                    );"
                                    data-total="<?= ($appCount + $penCount + $rejCount) ?>">
                                </div>

                                                                               

                                <ul class="pie-legend">
                                    <li data-count="<?= $appCount ?>">
                                        <span class="dot accent"></span>
                                        Complete (<?= round($pApp) ?>%)
                                    </li>

                                    <li data-count="<?= $penCount ?>">
                                        <span class="dot pending"></span>
                                        Pending (<?= round($pPen) ?>%)
                                    </li>

                                    <li data-count="<?= $rejCount ?>">
                                        <span class="dot error"></span>
                                        Fail (<?= round($pRej) ?>%)
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

    <div id="overlay" class="overlay">
        <div id="popup">
            <div id="popup-header">
                <h3>Create New Reward</h3>
            </div>
            <form class="reward-form" action="admin_create_reward.php" method="POST" enctype="multipart/form-data">
                <label>REWARD TITLE *</label>
                <input type="text" name="title" placeholder="e.g. Eco-Friendly Tote Bag">

                <label>CATEGORY *</label>
                <select name="category">
                    <option value="Merchandise">Merchandise</option>
                    <option value="Voucher">Voucher</option>
                    <option value="Donation">Donation</option>
                </select>

                <label>Description *</label>
                <input type="text" name="description" placeholder="e.g. Eco-Friendly Tote Bag made of cotton">

                <div id="form-row">
                    <div class="form-group">
                        <label>POINTS COST *</label>
                        <input type="number" name="points" value="500">
                    </div>
                    <div class="form-group">
                        <label>INITIAL STOCK *</label>
                        <input type="number" name="stock" value="50">
                    </div>
                </div>
                <label>IMAGE SEARCH QUERY</label>
                <div class="input-image">
                    <input type="file" name="reward_image" accept="image/*" required>
                </div>
                <div id="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closePopup()">Cancel</button>
                    <button type="submit" class="submit-btn" name="submit_reward" >Create Reward</button>
                </div>
            </form>
        </div>
    </div>

    <script src="./scripts/script.js"></script>
    <script src="./scripts/admin/admin_popup.js" defer></script>
    <script src="./scripts/admin/admin_reward.js" defer></script>
    <script src="./scripts/admin/admin_stats.js" defer></script>
</body>
        


