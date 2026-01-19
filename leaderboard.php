

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
            <div id="time-filter">
                <nav id="timefilters">
                    <button class="filter-button" data-filter="week">This Week</button>
                    <button class="filter-button" data-filter="month">This Month</button>
                    <button class="filter-button" data-filter="alltime">All Time</button>
                </nav>
            </div>
            <div id="top-achievements">
                <img src="./assets/target.svg">
                <h3>Top Achievements</h3>
            </div>
            <div id="achievements-grid">
                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/plastic.svg">
                    </div>
                    <p class="achievement-title">TOP PLASTIC SAVER</p>
                    <p class="achievement-name"><?= htmlspecialchars($topPlasticUser['username'] ?? 'No data yet') ?></p>
                    <p class="achievement-value green-text"><?= $topPlasticUser['plastic_saved']?? '0' ?> kg</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/leaf.svg">
                    </div>
                    <p class="achievement-title">MOST CO₂ OFFSET</p>                   
                    <p class="achievement-name"><?= htmlspecialchars($topCo2User['username']?? 'No data yet') ?></p>
                    <p class="achievement-value green-text"><?= $topCo2User['co2_offset'] ?? '0'?> tons</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/fire.svg">
                    </div>
                    <p class="achievement-title">LONGEST STREAK</p>
                    <p class="achievement-name"><?= htmlspecialchars($longestStreak['username']?? 'No data yet') ?></p>
                    <p class="achievement-value green-text"><?= $longestStreak['streak'] ?? '0'?> days</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/trophy.svg">
                    </div>
                    <p class="achievement-title">MOST ACTIONS</p>
                    <p class="achievement-name"><?= htmlspecialchars($mostAction['username']?? 'No data yet') ?></p>
                    <p class="achievement-value green-text"><?= $mostAction['action'] ?? '0'?> </p>
                </div>
            </div>
            <div id="stats-grid">
                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/trophy.svg">
                        <p class="stats-title">TOTAL USERS</p>
                    </div>
                    <p class="stats-value"><?= $totalUser['total-user'] ?? '0'?> </p>
                    <p class="stats-footer green-text"><?= $totalUser['growth'] ?? '0'?> this week</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/fire.svg">
                        <p class="stats-title">AVG STREAK</p>
                    </div>
                    <p class="stats-value"><?= $averageStreak['average-streak'] ?? '0'?></p>
                    <p class="stats-footer green-text">days</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/average.png">
                        <p class="stats-title">AVG POINTS</p>
                    </div>
                    <p class="stats-value"><?= $averagePoint['average-point'] ?? '0'?></p>
                    <p class="stats-footer green-text"><?= $averagePoint['growth'] ?? '0'?> from last month</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/increase.png">
                        <p class="stats-title">TOP SCORE</p>
                    </div>
                    <p class="stats-value"><?= $topScore['top-score'] ?? '0'?></</p>
                    <p class="stats-footer green-text"><?= htmlspecialchars($topScore['username']?? 'No data yet')?></p>
                </div>
            </div>

            <section id="global-rankings">
                <h2>Global Rankings</h2>
                <div class="user-card">
                    <div class="label-header">
                        <p>Compete with eco-warriors worldwide</p>
                    </div>

                    <div class="rank-card">
                        <div class="card-content">

                            <div class="user-section">
                                <div class="user-avatar">
                                    <img src="./assets/trophy.svg">
                                </div>
                                <div class="user-info">
                                    <span class="user-title"><?= htmlspecialchars($currentUser['username'] ?? 'Guest') ?></span>
                                    <span class="user-level"><?= htmlspecialchars($currentUser['title'] ?? 'New Member') ?></span>
                                </div>
                            </div>

                            <div class="user-stats-section">
                                <span class="user-rank-number">#<?= $currentUser['rank'] ?? 'N/A' ?></span>
                                <span class="user-points"><?= number_format($currentUser['points'] ?? 0) ?></span>
                            </div>
                        </div>
                    </div>
                    <div id="board-filter">
                        <nav class="board-filters" id="timeFilters">
                            <button class="board-button" data-filter="total-point">Total Point</button>
                            <button class="board-button" data-filter="plastic-impact">Plastic Impact</button>
                            <button class="board-button" data-filter="carbon-impact">Carbon Impact</button>
                        </nav>
                    </div>
                    <div class="leaderboard">
                        <?php 
                        $ranks = ['gold', 'silver', 'bronze'];
                        $badges = ['👑', '🥈', '🥉'];

                        for($i = 0; $i < 3; $i++): 
                            if(isset($leaderboard[$i])): 
                                $user = $leaderboard[$i];
                        ?>
                            <div class="card <?= $ranks[$i] ?>">
                                <div class="avatar">
                                    <img src="./assets/avatar.svg">
                                    <span class="badge"><?= $badges[$i] ?></span>
                                </div>
                                <h3><?=htmlspecialchars($user['username'] ?? 'Guest') ?></h3>
                                <p class="points"><?= number_format($user[$sortColumn]?? 0) ?></p>
                                <p class="label"><?= $unit ?></p>
                            </div>
                        <?php endif; endfor; ?>
                    </div>

                    <ul id="leaderboard-list">
                        <?php for($i = 3; $i < 10; $i++): 
                            if(isset($leaderboard[$i])): 
                                $user = $leaderboard[$i];
                        ?>
                            <li class="ranking-item">
                                <div class="rank-badge number-rank">#<?= $i + 1 ?></div>
                                <div class="user-avatar-container">
                                    <img src="<?= $imagePath ?>" alt="<?=htmlspecialchars($user['username'] ?? 'N/A') ?>" class="leaderboard-avatar">
                                </div>
                                <div class="user-info">
                                    <span class="user-name"><?= htmlspecialchars($user['username'] ??'Guest') ?></span>
                                    <span class="user-title"><?= htmlspecialchars($user['title'] ??'N/A') ?></span>                                </div>
                                <span class="user-points green-text"><?= number_format($user['points']??'0') ?></span>
                            </li>
                        <?php endif; endfor; ?>
                    </ul>

                </section>


        </div>
    </div>
    </div>
    </div>
</body>