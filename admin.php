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
                        <span id="total-reward">
                            <h5>Total Reward</h5>
                            <img src="./assets/target.svg">
                            <h3><?= $totalResult['count'] ?? 0?></h3>
                        </span>
                        <span id="low-stock">
                            <h5>Low Stock Alert</h5>
                            <img src="./assets/target.svg">
                            <h3><?= $lowStockResult['count'] ?? 0?></h3>
                            <h6>Require Attention</h6>
                        </span>
                    </div>
                    <div class="active-ended">
                        <span id="active">
                            <img src="./assets/target.svg">
                            <h5>Active</h5>
                            <h3><?= $activeResult['count'] ?? 0?></h3>
                        </span>
                        <span id="ended">
                            <img src="./assets/target.svg">
                            <h5>Ended</h5>
                            <h3><?= $endedResult['count'] ?? 0 ?></h3>
                        </span>
                    </div>
                </div>
            </div>
            <div id="filter" >
                <button id="all-reward" class="cls-btn active" onclick="changeFilter('pending',event)">All Reward</button>
                <button id="active" class="cls-btn" onclick="changeFilter('approved',event)">Active</button>
                <button id="discontinue" class="cls-btn" onclick="changeFilter('rejected',event)">Discontinued</button>
            </div>
        </aside>

        <main class="main">
            <section id="reward" class="page-content">
                <div id="header">
                    <div class="page-title">
                        <h3>Reward Management</h3>
                        <p>Last updated: <?php echo date("d F Y, h:i A", strtotime($lastUpdated)); ?></p>
                    </div>

                    <button class="conner-btn" onclick="openPopup()">+ Create Reward</button>
                </div>
            
                <div id="card-container">
                    <div class="card">
                        <div class="card-header">
                            <div class="user-info">
                                <div class="avatar">MT</div>
                                <div class="user-details">
                                    <span class="username">Mike T.</span>
                                    <span class="timestamp">Nov 20 at 09:30 AM</span>
                                </div>
                            </div>
                            <div class="badges">
                                <span class="badge featured">FEATURED</span>
                                <span class="badge active">ACTIVE</span>
                            </div>
                        </div>
                    
                        <div class="product-title-row">
                            <h2>Eco-Friendly Tote Bag</h2>
                            <div class="tags">
                                <span class="type-tag">MERCHANDISE</span>
                                <span class="pts-tag">500 pts</span>
                            </div>
                        </div>
                    
                        <div class="image-container">
                            <img src="./assets/bag.png">
                        </div>
                    
                        <div class="controls">
                            <div class="control-row">
                                <span>Stock:</span>
                                <div class="stock-count"> 
                                    <h5>12</h5>
                                    <span class="edit-icon">✎</span>
                                </div>
                            </div>
                        
                            <div class="control-row">
                                <span>Auto-discontinue when out of stock</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        
                            <div class="control-row divider-top">
                                <span>Voucher Codes</span>
                                <span class="voucher-link">3 codes</span>
                            </div>
                        </div>
                        <button class="discontinue-btn">Discontinue</button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="user-info">
                                <div class="avatar">MT</div>
                                <div class="user-details">
                                    <span class="username">Mike T.</span>
                                    <span class="timestamp">Nov 20 at 09:30 AM</span>
                                </div>
                            </div>
                            <div class="badges">
                                <span class="badge featured">FEATURED</span>
                                <span class="badge active">ACTIVE</span>
                            </div>
                        </div>
                    
                        <div class="product-title-row">
                            <h2>Eco-Friendly Tote Bag</h2>
                            <div class="tags">
                                <span class="type-tag">MERCHANDISE</span>
                                <span class="pts-tag">500 pts</span>
                            </div>
                        </div>
                    
                        <div class="image-container">
                            <img src="./assets/bag.png">
                        </div>
                    
                        <div class="controls">
                            <div class="control-row">
                                <span>Stock:</span>
                                <div class="stock-count"> 
                                    <h5>12</h5>
                                    <span class="edit-icon">✎</span>
                                </div>
                            </div>
                        
                            <div class="control-row">
                                <span>Auto-discontinue when out of stock</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        
                            <div class="control-row divider-top">
                                <span>Voucher Codes</span>
                                <span class="voucher-link">3 codes</span>
                            </div>
                        </div>
                        <button class="discontinue-btn">Discontinue</button>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="user-info">
                                <div class="avatar">MT</div>
                                <div class="user-details">
                                    <span class="username">Mike T.</span>
                                    <span class="timestamp">Nov 20 at 09:30 AM</span>
                                </div>
                            </div>
                            <div class="badges">
                                <span class="badge featured">FEATURED</span>
                                <span class="badge active">ACTIVE</span>
                            </div>
                        </div>
                    
                        <div class="product-title-row">
                            <h2>Eco-Friendly Tote Bag</h2>
                            <div class="tags">
                                <span class="type-tag">MERCHANDISE</span>
                                <span class="pts-tag">500 pts</span>
                            </div>
                        </div>
                    
                        <div class="image-container">
                            <img src="./assets/bag.png">
                        </div>
                    
                        <div class="controls">
                            <div class="control-row">
                                <span>Stock:</span>
                                <div class="stock-count"> 
                                    <h5>12</h5>
                                    <span class="edit-icon">✎</span>
                                </div>
                            </div>
                        
                            <div class="control-row">
                                <span>Auto-discontinue when out of stock</span>
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        
                            <div class="control-row divider-top">
                                <span>Voucher Codes</span>
                                <span class="voucher-link">3 codes</span>
                            </div>
                        </div>
                        <button class="discontinue-btn">Discontinue</button>
                    </div>
                </div>
            </section>
            <section>
                <section id="stats" class="page-content" style="display: none;">
                    <div id="header">
                        <div class="page-title">
                            <h3>Statistics Dashboard</h3>
                            <p>Last updated: <?php echo date("d F Y, h:i A", strtotime($lastUpdated)); ?></p>
                        </div>

                    <button class="conner-btn" onclick="Export()">Export Report</button>
                </div>
                <div class="dashboard-container">
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Total Users</p>
                            <h2 class="value">245</h2>
                            <p class="trend positive">+12% from last week</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
              
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Tasks Completed</p>
                            <h2 class="value">385</h2>
                            <p class="trend positive">+8% from last week</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
              
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Avg. Completion Time</p>
                            <h2 class="value">9.8 min</h2>
                            <p class="trend negative">-15% from last week</p>
                            <img src="./assets/target.svg">
                        </div>
                    </div>
              
                    <div class="stat-card">
                        <div class="content">
                            <p class="label">Success Rate</p>
                            <h2 class="value">96.3%</h2>
                            <p class="trend positive">+2.1% from last week</p>
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
                    <div class="chart">
                        <h3>Task Completion Trend</h3>
                        <div id="chart-wrapper">
                            <div class="y-axis">
                                <span>100</span>
                                <span>75</span>
                                <span>50</span>
                                <span>25</span>
                                <span>0</span>
                            </div>

                            <div class="bar-chart">
                                <div class="bar" style="--value: 70"><span>Mon</span></div>
                                <div class="bar" style="--value: 85"><span>Tue</span></div>
                                <div class="bar" style="--value: 60"><span>Wed</span></div>
                                <div class="bar" style="--value: 90"><span>Thu</span></div>
                                <div class="bar" style="--value: 75"><span>Fri</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="chart">
                        <h3>Task Status Distribution</h3>
                        <div id="pie-wrapper">

                            <div class="pie-chart">
                                <li><span class="pie"></span> Complete (40%)</li>
                                <li><span class="pie"></span> Pending (35%)</li>
                                <li><span class="pie"></span> fail (25%)</li>
                            </div>
                        </div>
                    </div>
                </div>



            </section>
        </main>
    </div>
    

</body>
        


<div id="overlay" class="overlay">
    <div id="popup">
        <div id="popup-header">
            <h3>Create New Reward</h3>
        </div>
        <form class="reward-form" action="create_reward.php" method="POST">
            <label>REWARD TITLE *</label>
            <input type="text" name="title" placeholder="e.g. Eco-Friendly Tote Bag">
            
            <label>CATEGORY *</label>
            <select name="category">
                <option>Merchandise</option>
                <option>Voucher</option>
            </select>
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
            <div class="input-with-icon">
                <input type="text" placeholder="e.g. sustainable bag, coffee voucher">
            </div>
            <div class="auto-discontinue-box">
                <div>
                    <p>Auto-discontinue when out of stock</p>
                    <small>Automatically disable reward when stock reaches 0</small>
                </div>
                <label class="switch"> 
                    <input type="checkbox" name="auto_discontinue" value="1" checked>
                    <span class="slider"></span>
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closePopup()">Cancel</button>
                <button type="submit" class="submit-btn" name="submit_reward" >Create Reward</button>
            </div>
        </form>
    </div>
</div>

    
<script src=" ./scripts/script.js"></script>
<script src="./scripts/admin/admin_create_reward.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
