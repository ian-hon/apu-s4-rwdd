<div id="submissions">
    <div id="query">
        <div id="search">
            <input class="border" placeholder="🔍 search submissions">
        </div>
        <h6 id="filter-subtitle">SHOW ONLY:</h6>
        <div id="filter">
            <h6 id="pending" class="border" onclick="changeFilter('pending')">PENDING</h6>
            <h6 id="approved" class="border" onclick="changeFilter('approved')">APPROVED</h6>
            <h6 id="rejected" class="border" onclick="changeFilter('rejected')">REJECTED</h6>
        </div>
    </div>
    <hr>
    <div id="overview">
        <h5>OVERVIEW</h5>
        <div id="pie">
        </div>
        <div id="statistics">
            <span id="pending">
                <h5>14 PENDING</h5>
                <h5>15%</h5>
            </span>
            <span id="approved">
                <h5>6 APPROVED</h5>
                <h5>15%</h5>
            </span>
            <span id="rejected">
                <h5>7 REJECTED</h5>
                <h5>15%</h5>
            </span>
        </div>
    </div>
</div>