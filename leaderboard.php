<?php
ob_start();
include 'api/reward/fetch_all.php';
include 'api/users/fetch_all.php';
include 'api/submission/fetch_all.php';
include 'api/task/fetch_all.php';
include 'api/points/fetch_all.php';
include 'api/roles/fetch_all.php';
ob_end_clean();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$sessionUserId = $_SESSION['user_id'] ?? null;

// --- 1. Filter and Calculate User Data ---
$participants = array_filter($users, function($u) {
    return isset($u['role']) && $u['role'] === 'user';
});

$totalPointsSum = 0;

foreach ($participants as &$u) {
    $userId = $u['username']; 
    $u['points'] = 0;
    $u['plastic_impact'] = 0;
    $u['carbon_impact'] = 0;
    $u['action_count'] = 0;

    foreach ($submissions as $sub) {
        if ($sub['user'] === $userId && $sub['status'] === 'approved') {
            $u['action_count']++;
            
            foreach ($points as $p) {
                if ($p['submission'] === $sub['ID']) {
                    $u['points'] += (int)$p['amount'];
                }
            }

            $task = $tasks[$sub['task_ID']] ?? null;
            if ($task) {
                $impactValue = (int)($sub['action_count_val'] ?? 1) * (int)($task['goal_contribution'] ?? 0);
                if ($task['goal_type'] === 'plastic') $u['plastic_impact'] += $impactValue;
                if ($task['goal_type'] === 'carbon')  $u['carbon_impact'] += $impactValue;
            }
        }
    }
    $totalPointsSum += $u['points'];
}
unset($u);

// --- 2. Hall of Fame Calculations ---
$topPlasticUser = ['username' => 'No data', 'plastic_saved' => 0];
$topCo2User     = ['username' => 'No data', 'co2_offset' => 0];
$mostAction     = ['username' => 'No data', 'action' => 0];
$impactMaster   = ['username' => 'No data', 'total_impact' => 0];
$topScoreVal    = 0;
$topScoreName   = 'No data';

foreach ($participants as $u) {
    if ($u['plastic_impact'] > $topPlasticUser['plastic_saved']) {
        $topPlasticUser = ['username' => $u['name'], 'plastic_saved' => $u['plastic_impact']];
    }
    if ($u['carbon_impact'] > $topCo2User['co2_offset']) {
        $topCo2User = ['username' => $u['name'], 'co2_offset' => $u['carbon_impact']];
    }
    if ($u['action_count'] > $mostAction['action']) {
        $mostAction = ['username' => $u['name'], 'action' => $u['action_count']];
    }
    if ($u['points'] > $topScoreVal) {
        $topScoreVal = $u['points'];
        $topScoreName = $u['name'];
    }
    $combined = $u['plastic_impact'] + $u['carbon_impact'];
    if ($combined > $impactMaster['total_impact']) {
        $impactMaster = ['username' => $u['name'], 'total_impact' => $combined];
    }
}

// --- 3. Sorting Logic ---

$sortColumn = $_GET['sort'] ?? 'points';
$unit = 'Points';

if ($sortColumn === 'plastic_impact') {
    $unit = 'kg';
} elseif ($sortColumn === 'carbon_impact') {
    $unit = 'tons';
} else {
    $sortColumn = 'points'; 
}

// FORCE DESCENDING ORDER
usort($participants, function($a, $b) use ($sortColumn) {
    $valA = (int)($a[$sortColumn] ?? 0);
    $valB = (int)($b[$sortColumn] ?? 0);
    
    if ($valA == $valB) return 0;
    return ($valB > $valA) ? 1 : -1; 
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

if (!$currentUser) {
    $currentUser = ['name' => 'Guest', 'rank' => 'N/A', 'points' => 0, 'title' => 'Visitor'];
}

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
            <div id="header">
                <div id="leaderboard">
                    <img src="./assets/trophy.svg">
                    <h2>Leaderboard</h2>
                </div>
                <img onclick="toggleNavbar()" id="burger" src="./assets/burger.svg">
            </div>

            <div id="top-achievements">
                <img src="./assets/target.svg">
                <h3>Top Achievements</h3>
            </div>

            <div id="achievements-grid">
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/plastic.svg"></div>
                    <p class="achievement-title">TOP PLASTIC SAVER</p>
                    <p class="achievement-name"><?= htmlspecialchars($topPlasticUser['username']) ?></p>
                    <p class="achievement-value green-text"><?= $topPlasticUser['plastic_saved'] ?> kg</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/leaf.svg"></div>
                    <p class="achievement-title">MOST CO₂ OFFSET</p>                   
                    <p class="achievement-name"><?= htmlspecialchars($topCo2User['username']) ?></p>
                    <p class="achievement-value green-text"><?= $topCo2User['co2_offset'] ?> tons</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/fire.svg"></div>
                    <p class="achievement-title">IMPACT MASTER</p>
                    <p class="achievement-name"><?= htmlspecialchars($impactMaster['username']) ?></p>
                    <p class="achievement-value green-text"><?= $impactMaster['total_impact'] ?> units</p>
                </div>
                <div class="achievement-card">
                    <div class="card-icon"><img src="./assets/trophy.svg"></div>
                    <p class="achievement-title">MOST ACTIONS</p>
                    <p class="achievement-name"><?= htmlspecialchars($mostAction['username']) ?></p>
                    <p class="achievement-value green-text"><?= $mostAction['action'] ?> </p>
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
                                <span class="user-points"><?= number_format($currentUser['points']) ?></span>
                            </div>
                        </div>
                    </div>

                    <div id="board-filter">
                        <nav class="board-filters" id="timeFilters">
                            <button type="button" class="board-button active" onclick="filterLeaderboard('points')">Total Point</button>
                            <button type="button" class="board-button" onclick="filterLeaderboard('plastic_impact')">Plastic Impact</button>
                            <button type="button" class="board-button" onclick="filterLeaderboard('carbon_impact')">Carbon Impact</button>
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
                    ?>
                        <div class="card <?= $ranks[$index] ?>">
                            <div class="avatar">
                                <img src="./assets/avatar.svg">
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
                                $imgPath = $user['profile_pic'] ?? './assets/avatar.svg';
                        ?>
                            <li class="ranking-item">
                                <div class="rank-badge number-rank">#<?= $i + 1 ?></div>
                                <div class="user-avatar-container"><img src="<?= $imgPath ?>" class="leaderboard-avatar"></div>
                                <div class="user-info">
                                    <span class="user-name"><?= htmlspecialchars($user['name']) ?></span>
                                    <span class="user-title"><?= htmlspecialchars($user['title'] ?? 'Eco Warrior') ?></span>
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

    <script>
function filterLeaderboard(sortType) {
    // 1. Get the current URL
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortType);

    // 2. Fetch the new data from the same page using the sort parameter
    // We append a custom header so PHP knows we only want the leaderboard part
    fetch(url.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // 3. Create a temporary DOM element to parse the HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // 4. Only replace the specific sections that need updating
        const newTop3 = doc.querySelector('.leaderboard').innerHTML;
        const newList = doc.querySelector('#leaderboard-list').innerHTML;
        const newRankCard = doc.querySelector('.rank-card').innerHTML;

        document.querySelector('.leaderboard').innerHTML = newTop3;
        document.querySelector('#leaderboard-list').innerHTML = newList;
        document.querySelector('.rank-card').innerHTML = newRankCard;

        // 5. Update the URL in the browser bar WITHOUT reloading
        window.history.pushState({ path: url.href }, '', url.href);
        
        // 6. Update active button styles
        document.querySelectorAll('.board-button').forEach(btn => {
            btn.classList.remove('active');
            if(btn.getAttribute('onclick').includes(sortType)) btn.classList.add('active');
        });
    })
    .catch(error => console.error('Error filtering leaderboard:', error));
}
</script>
</body>
</html>