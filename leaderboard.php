<?php
ob_start();
include 'api/reward/fetch_all.php';
include 'api/users/fetch_all.php';
include 'api/submission/fetch_all.php';
include 'api/task/fetch_all.php';
include 'api/points/fetch_all.php';
include 'api/roles/fetch_all.php';
include 'api/credentials/fetch_all.php';
include 'api/goals/functions.php'; 
ob_end_clean();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sessionUserId = $_SESSION['username'] ?? null;

// --- 1. Data Processing ---
$participants = array_filter($users, function($u) {
    return isset($u['role']) && $u['role'] === 'user';
});

$totalPointsSum = 0;

foreach ($participants as &$u) {
    $username = $u['username'];
    
    $userImpacts = goals_contributions_all($username, NULL, false);
    
    $u['points'] = 0;
    $u['plastic_impact'] = 0;
    $u['electric_impact'] = 0;
    $u['action_count'] = 0;

    foreach ($submissions as $sub) {
        if ($sub['user'] === $username && $sub['status'] === 'approved') {
            $u['action_count']++;
            foreach ($points as $p) {
                if ($p['submission'] === $sub['ID']) {
                    $u['points'] += (int)$p['amount'];
                }
            }
        }
    }

    foreach ($userImpacts as $goal) {
        if ($goal['goal_type'] === 'plastic') $u['plastic_impact'] += $goal['total'];
        if ($goal['goal_type'] === 'electric') $u['electric_impact'] += $goal['total'];
    }

    $totalPointsSum += $u['points'];
}
unset($u);

// --- 2. Hall of Fame Logic ---
$topPlasticUser = ['username' => 'No data', 'val' => 0];
$topElectricUser = ['username' => 'No data', 'val' => 0];
$mostAction = ['username' => 'No data', 'val' => 0];
$impactMaster = ['username' => 'No data', 'val' => 0];
$topScoreVal = 0;
$topScoreName = 'No data';

foreach ($participants as $u) {
    if ($u['plastic_impact'] > $topPlasticUser['val']) 
        $topPlasticUser = ['username' => $u['name'], 'val' => $u['plastic_impact']];
    
    if ($u['electric_impact'] > $topElectricUser['val']) 
        $topElectricUser = ['username' => $u['name'], 'val' => $u['electric_impact']];

    if ($u['action_count'] > $mostAction['val']) 
        $mostAction = ['username' => $u['name'], 'val' => $u['action_count']];

    if ($u['points'] > $topScoreVal) {
        $topScoreVal = $u['points'];
        $topScoreName = $u['name'];
    }

    $combined = $u['plastic_impact'] + $u['electric_impact'];
    if ($combined > $impactMaster['val']) 
        $impactMaster = ['username' => $u['name'], 'val' => $combined];
}

// --- 3. Sorting & Layout Mapping ---
$sortColumn = $_GET['sort'] ?? 'points';
$unit = 'Points';

if ($sortColumn === 'plastic_impact') {
    $unit = 'kg';
} elseif ($sortColumn === 'electric_impact') {
    $unit = 'kWh';
} else {
    $sortColumn = 'points';
}

usort($participants, function($a, $b) use ($sortColumn) {
    return (int)($b[$sortColumn] ?? 0) <=> (int)($a[$sortColumn] ?? 0);
});

$leaderboard = array_values($participants);

$currentUser = null;
foreach ($leaderboard as $index => $u) {
    if ($u['username'] === $sessionUserId) {
        $currentUser = $u;
        $currentUser['rank'] = $index + 1;
        break;
    }
}
$currentUser = $currentUser ?? ['name' => 'Guest', 'rank' => 'N/A', 'points' => 0, 'title' => 'Visitor'];

$totalUserCount = count($participants);
$avgActionsVal = ($totalUserCount > 0) ? round(count($submissions) / $totalUserCount, 1) : 0;
$avgPointsVal = ($totalUserCount > 0) ? round($totalPointsSum / $totalUserCount) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard | EcoQuest</title>
    <link rel="stylesheet" href="./styles/style.css">   
    <link rel="stylesheet" href="./styles/leaderboard.css">
    <link rel="stylesheet" href="./styles/leaderboard2.css">
</head>
<body>
    <div id="bg-color">
        <div id="elements">
            <?php $headerTitle = "Leaderboard"; include './components/header.php'; ?>

            <div id="top-achievements">
                <img src="./assets/target.svg">
                <h3>Top Achievements</h3>
            </div>

            <div id="achievements-grid">
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/plastic.svg"></div>
                    <p class="achievement-title">TOP PLASTIC SAVER</p>
                    <p class="achievement-name"><?= htmlspecialchars($topPlasticUser['username']) ?></p>
                    <p class="achievement-value green-text"><?= $topPlasticUser['val'] ?> kg</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/leaf.svg"></div>
                    <p class="achievement-title">MOST ELECTRICITY SAVED</p>                   
                    <p class="achievement-name"><?= htmlspecialchars($topElectricUser['username']) ?></p>
                    <p class="achievement-value green-text"><?= $topElectricUser['val'] ?> kWh</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/fire.svg"></div>
                    <p class="achievement-title">IMPACT MASTER</p>
                    <p class="achievement-name"><?= htmlspecialchars($impactMaster['username']) ?></p>
                    <p class="achievement-value green-text"><?= $impactMaster['val'] ?> units</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/trophy.svg"></div>
                    <p class="achievement-title">MOST ACTIONS</p>
                    <p class="achievement-name"><?= htmlspecialchars($mostAction['username']) ?></p>
                    <p class="achievement-value green-text"><?= $mostAction['val'] ?> </p>
                </div>
            </div>

            <div id="stats-grid">
                <div class="stats-card">
                    <div class="stats-header"><img src="./assets/trophy.svg"><p class="stats-title">TOTAL USERS</p></div>
                    <p class="stats-value"><?= $totalUserCount ?></p>
                    <p class="stats-footer green-text">Eco-Worrior</p>
                </div>
                <div class="stats-card">
                    <div class="stats-header"><img src="./assets/fire.svg"><p class="stats-title">AVG ACTION</p></div>
                    <p class="stats-value"><?= $avgActionsVal ?></p>
                    <p class="stats-footer green-text">per user</p>
                </div>
                <div class="stats-card">
                    <div class="stats-header"><img src="./assets/average.png"><p class="stats-title">AVG POINTS</p></div>
                    <p class="stats-value"><?= $avgPointsVal ?></p>
                    <p class="stats-footer green-text">avg per user</p>
                </div>
                <div class="stats-card">
                    <div class="stats-header"><img src="./assets/increase.png"><p class="stats-title">TOP SCORE</p></div>
                    <p class="stats-value"><?= $topScoreVal ?></p>
                    <p class="stats-footer green-text"><?= htmlspecialchars($topScoreName) ?></p>
                </div>
            </div>

            <section id="global-rankings">
                <h2>Global Rankings</h2>
                <div class="user-card">
                    <div class="label-header"><p>Compete with eco-warriors worldwide</p></div>
                    <div class="rank-card">
                        <div class="card-content">
                            <div class="user-section">
                                <div class="user-avatar"><img src="./assets/leaf.svg"></div>
                                <div class="user-info">
                                    <span class="user-title"><?= htmlspecialchars($currentUser['name']) ?></span>
                                    <span class="user-level"><?= htmlspecialchars($currentUser['title'] ?? 'Member') ?></span>
                                </div>
                            </div>
                            <div class="user-stats-section">
                                <span class="user-rank-number">#<?= $currentUser['rank'] ?></span>
                                <span class="user-points"><?= number_format($currentUser[$sortColumn] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>

                    <div id="board-filter">
                        <nav class="board-filters" id="timeFilters">
                            <button type="button" class="board-button <?= $sortColumn == 'points' ? 'active' : '' ?>" onclick="filterLeaderboard('points')">Total Point</button>
                            <button type="button" class="board-button <?= $sortColumn == 'plastic_impact' ? 'active' : '' ?>" onclick="filterLeaderboard('plastic_impact')">Plastic Impact</button>
                            <button type="button" class="board-button <?= $sortColumn == 'electric_impact' ? 'active' : '' ?>" onclick="filterLeaderboard('electric_impact')">Electric Impact</button>
                        </nav>
                    </div>

                    <div class="leaderboard">
                        <?php 
                        $podiumMap = [1, 0, 2]; 
                        $ranks = [1 => 'silver', 0 => 'gold', 2 => 'bronze'];
                        $badges = [1 => '🥈', 0 => '👑', 2 => '🥉'];
                
                        foreach($podiumMap as $index): 
                            if(isset($leaderboard[$index])): 
                                $user = $leaderboard[$index];
                                $imgPath = $user['profile_pic'] ?? './assets/avatar.png';
                        ?>
                            <div class="card <?= $ranks[$index] ?>">
                                <div class="avatar">
                                    <div class="user-avatar-container"><img src="<?= $imgPath ?>" class="leaderboard-avatar"></div>
                                    <span class="badge"><?= $badges[$index] ?></span>
                                </div>
                                <h3><?= htmlspecialchars($user['name']) ?></h3>
                                <p class="user-points green-text"><?= number_format($user[$sortColumn] ?? 0) ?></p>
                                <p class="label"><?= $unit ?></p>
                            </div>
                        <?php endif; endforeach; ?>
                    </div>

                    <ul id="leaderboard-list">
                        <?php for($i = 3; $i < 10; $i++): 
                            if(isset($leaderboard[$i])): 
                                $user = $leaderboard[$i];
                                $imgPath = $user['profile_pic'] ?? './assets/avatar.png';
                        ?>
                            <li class="ranking-item">
                                <div class="rank-badge number-rank">#<?= $i + 1 ?></div>
                                <div class="user-avatar-container"><img src="<?= $imgPath ?>" class="leaderboard-avatar"></div>
                                <div class="user-info">
                                    <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                                </div>
                                <span class="user-points green-text">
                                    <?= number_format($user[$sortColumn] ?? 0) ?> <?= $unit ?>
                                </span>
                            </li>
                        <?php endif; endfor; ?>
                    </ul>
                </div>
            </section>
        </div>
    </div>
    <script src="./scripts/leaderboard.js" defer></script>
</body>