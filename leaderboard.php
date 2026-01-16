<?php
include './api/conn.php'; // connects to the database

$query =  'select * from ecoquest.USERS where username = "user1"';
$result = mysqli_query($dbConnection, $query); // $dbConnection comes from conn.php
$user = mysqli_fetch_assoc($result); // fetch_assoc gets the first result and stores it inside $user

// refer line 50

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
        <div id="element">
            <div id="header">
                <div id="leaderboard">
                    <img src="./assets/trophy.svg">
                    <h2>Leaderboard</h2>
                </div>
                <img onclick="toggleNavbar()" id="burger" src="./assets/burger.svg">
            </div>
            <div id="time-filter">
                <nav class="time-filters" id="timeFilters">
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
                    <p class="achievement-name">Sarah Chen</p>
                    <input type="text" id="achievement-name" value="<?php echo $user['username']; ?>" required readonly>
                    <p class="achievement-value green-text">142 kg</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/leaf.svg">
                    </div>
                    <p class="achievement-title">MOST CO₂ OFFSET</p>
                    <p class="achievement-name">Marcus Rodriguez</p>
                    <p class="achievement-value green-text">18.2 tons</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/fire.svg">
                    </div>
                    <p class="achievement-title">LONGEST STREAK</p>
                    <p class="achievement-name">Sarah Chen</p>
                    <p class="achievement-value green-text">28 days</p>
                </div>

                <div class="achievement-card">
                    <div class="card-icon">
                        <img src="./assets/trophy.svg">
                    </div>
                    <p class="achievement-title">MOST ACTIONS</p>
                    <p class="achievement-name">Emma Thompson</p>
                    <p class="achievement-value green-text">312 completed</p>
                </div>
            </div>
            <div id="stats-grid">
                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/trophy.svg">
                        <p class="stats-title">TOTAL USERS</p>
                    </div>
                    <p class="stats-value">2,847</p>
                    <p class="stats-footer green-text">↑ 12% this week</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/fire.svg">
                        <p class="stats-title">AVG STREAK</p>
                    </div>
                    <p class="stats-value">14.2</p>
                    <p class="stats-footer green-text">days</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/wave.svg">
                        <p class="stats-title">AVG POINTS</p>
                    </div>
                    <p class="stats-value">3,584</p>
                    <p class="stats-footer green-text">↑ 8% from last month</p>
                </div>

                <div class="stats-card">
                    <div class="stats-header">
                        <img src="./assets/up-arrow.svg">
                        <p class="stats-title">TOP SCORE</p>
                    </div>
                    <p class="stats-value">4,850</p>
                    <p class="stats-footer green-text">Sarah Chen</p>
                </div>
            </div>

            <section id="global-rankings">
                <h2>Global Rankings</h2>
                <div id="user-card">
                    <div id="label-header">
                        <p>Compete with eco-warriors worldwide</p>
                    </div>

                    <div id="rank-card">
                        <div id="card-content">

                            <div id="user-section">
                                <div id="user-avatar">
                                    <span>EW</span>
                                </div>
                                <div id="user-info">
                                    <span class="user-title">Your Rank</span>
                                    <span class="user-level">Intermediate</span>
                                </div>
                            </div>

                            <div id="user-stats-section">
                                <span class="user-rank-number">#12</span>
                                <span class="user-points">2340 pts</span>
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
                        <div class="card gold">
                            <div class="avatar">
                                <img src="./assets/avatar.svg">
                                <span class="badge">👑</span>
                            </div>
                            <h3>Sarah M.</h3>
                            <p class="points">4520</p>
                            <p class="label">pts</p>
                        </div>

                        <div class="card silver">
                            <div class="avatar">
                                <img src="./assets/avatar.svg">
                                <span class="badge">🥈</span>
                            </div>
                            <h3>Mike T.</h3>
                            <p class="points">3890</p>
                            <p class="label">pts</p>
                        </div>

                        <div class="card bronze">
                            <div class="avatar">
                                <img src="./assets/avatar.svg">
                                <span class="badge">🥉</span>
                            </div>
                            <h3>Emma L.</h3>
                            <p class="points">3654</p>
                            <p class="label">pts</p>
                        </div>
                    </div>

                    <ul id="leaderboard-list">

                        <li class="ranking-item">
                            <div class="rank-badge number-rank">#4</div>
                            <div class="user-initials">SC</div>
                            <div class="user-info">
                                <span class="user-name">Sarah Chen</span>
                                <span class="user-title">Top Plastic Saver</span>
                                <span class="user-metric">142 kg</span>
                            </div>
                            <div class="metrics">
                                <div class="metric-item">
                                    <img src="./assets/fire.svg">
                                    <span>28 days</span>
                                </div>
                                <div class="metric-item">
                                    <img src="./assets/leaf.svg" alt="CO2">
                                    <span>12.5t CO₂</span>
                                </div>
                            </div>
                            <span class="user-points green-text">4,850</span>
                        </li>

                        <li class="ranking-item">
                            <div class="rank-badge number-rank">#5</div>
                            <div class="user-initials">AK</div>
                            <div class="user-info">
                                <span class="user-name">Alex Kim</span>
                                <span class="user-title">Consistent Contributor</span>
                                <span class="user-metric">115 kg</span>
                            </div>
                            <div class="metrics">
                                <div class="metric-item">
                                    <img src="./assets/fire.svg">
                                    <span>12 days</span>
                                </div>
                                <div class="metric-item">
                                    <img src="./assets/leaf.svg">
                                    <span>5.2t CO₂</span>
                                </div>
                            </div>
                            <span class="user-points green-text">4,280</span>
                        </li>

                        <li class="ranking-item">
                            <div class="rank-badge number-rank">#6</div>
                            <div class="user-initials">ET</div>
                            <div class="user-info">
                                <span class="user-name">Emma Thompson</span>
                                <span class="user-title">Most Actions</span>
                                <span class="user-metric">312 completed</span>
                            </div>
                            <div class="metrics">
                                <div class="metric-item">
                                    <img src="./assets/fire.svg">
                                    <span>18 days</span>
                                </div>
                                <div class="metric-item">
                                    <img src="./assets/leaf.svg">
                                    <span>8.7t CO₂</span>
                                </div>
                            </div>
                            <span class="user-points green-text">3,950</span>
                        </li>

                    </ul>
            </section>


        </div>
    </div>
    </div>
    </div>
</body>