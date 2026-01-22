(function () {
    const container = document.querySelector("#page #content #submissions #container");
    const lastUpdatedElement = document.querySelector("#page #content #submissions #header #last-updated");
    const submissionCountElement = document.querySelector("#page #content #submissions #header #submission-count");

    const sidebar = document.querySelector("#page #sidebar #sidebar-content #submissions #overview");
    const sidebarPie = sidebar.querySelector("#pie");
    const sidebarStatistics = sidebar.querySelector("#statistics");

    const sidebarQueryInput = document.querySelector("#page #sidebar #sidebar-content #submissions #query #search input");

    var submissions = {};
    var tasks = {};

    var ratios = {
        pending: 100,
        approved: 0,
        rejected: 0,
    };

    let currentFilter = '';
    let currentQuery = '';

    // #region sidebar related
    function changeFilter(f) {
        currentFilter = currentFilter == f ? '' : f;
        sidebarElement.setAttribute("data-filter", currentFilter);
        render();
    }
    window.changeFilter = changeFilter;

    function changeQuery(q) {
        currentQuery = q;
        sidebarElement.setAttribute("data-query", currentQuery);
        render();
    }
    // #endregion

    function fetchData() {
        fetch('/api/task/fetch_all.php')
            .then((t) => t.json())
            .then((t) => {
                tasks = t;

                fetch('/api/submission/fetch_all.php')
                    .then((e) => e.json())
                    .then((e) => {
                        submissions = e;

                        lastUpdatedElement.innerHTMl = `last updated at ${(new Date()).toString()}`;
                        submissionCountElement.innerHTML = `(${Object.keys(submissions).length} submissions)`;

                        ratios = {
                            approved: Object.values(submissions).filter(s => s['status'] == 'approved').length,
                            rejected: Object.values(submissions).filter(s => s['status'] == 'rejected').length,
                        };
                        ratios.pending = Object.keys(submissions).length - ratios.approved - ratios.rejected;

                        render();
                    })
            });
    }

    let getParsedActionCount = (submission) => {
        let t = tasks[submission['task_ID']];

        // we arent tracking counts of 'excess' actions, but actions itself
        // so when these submissions are added into the db, they will all start from 0
        // thus, lets just assume 0 = task.target

        // whenever action_count is changed, we set a limit that it can never go below task.target
        return (submission['action_count'] == 0) ? t['target'] : submission['action_count'];
    }

    let getParsedExcessCount = (submission) => {
        let t = tasks[submission['task_ID']];
        return (submission['action_count'] != 0) ? submission['action_count'] - t['target'] : 0;
    };

    let totalPoints = (submission) => {
        let t = tasks[submission['task_ID']];
        return t['reward_rate'] * getParsedActionCount(submission);
    };

    let humanReadableTime = (epoch) => {
        let d = new Date(epoch * 1000);
        let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Sep', 'Oct', 'Nov', 'Dec'];
        return `${d.getDate()} ${months[d.getMonth()]} at ${("0" + d.getHours()).slice(-2)}:${("0" + d.getMinutes()).slice(-2)}`; // https://stackoverflow.com/a/30272803/13684100
    }

    function renderSidebar() {
        let approved = parseInt((ratios.approved / Object.keys(submissions).length) * 100);
        let rejected = parseInt((ratios.rejected / Object.keys(submissions).length) * 100);
        let pending = 100 - approved - rejected;

        sidebarStatistics.querySelector("#pending").innerHTML = `<h5>${ratios.pending} PENDING</h5><h5>${pending}%</h5>`;
        sidebarStatistics.querySelector("#approved").innerHTML = `<h5>${ratios.approved} APPROVED</h5><h5>${approved}%</h5>`;
        sidebarStatistics.querySelector("#rejected").innerHTML = `<h5>${ratios.rejected} REJECTED</h5><h5>${rejected}%</h5>`;

        approved *= 3.60;
        rejected *= 3.60;
        pending *= 3.60;

        // rejected, approved, pending
        sidebarPie.innerHTML = `<div id="rejected" style="background: conic-gradient(var(--error) ${rejected}deg, transparent ${rejected}deg)"></div>
        <div id="approved" style="background: conic-gradient(transparent ${rejected}deg, var(--accent) ${rejected}deg ${approved + rejected}deg, transparent ${approved + rejected}deg)"></div>
        <div id="pending" style="background: conic-gradient(transparent ${rejected + approved}deg, var(--tertiary-background) ${rejected + approved}deg 360deg, transparent 360deg)"></div>`;
    }

    function render() {
        let result = '';
        Object.values(submissions).forEach((s) => {
            if (currentFilter && (s['status'] != currentFilter)) {
                return;
            }

            let t = tasks[s['task_ID']];
            if (currentQuery && ([t['title'], t['description'], s['user'], humanReadableTime(s['submitted_timestamp'])].filter((text) => text.includes(currentQuery)).length == 0)) {
                return;
            }

            result += `<div class="submission-card" data-status="${s['status']}" data-occurance="${t['occurance_type']}">
                <div id="header">
                    <!-- is there a justify-self thing that can do this? -->
                    <span>
                        <img id="pfp" src="./assets/fire.svg">
                        <div id="info">
                            <h4>${s['user']}</h4>
                            <h5>${humanReadableTime(s['submitted_timestamp'])}</h5>
                        </div>
                    </span>
                    <h5 id="tag" class="border">${s['status'].toUpperCase()}</h5>
                </div>
                <div id="image">
                    <img src="./media/submissions/recycle.jpg">
                </div>
                <div id="data" class="border">
                    <div id="task">
                        <div id="info">
                            <h5 id="title">${t['title']}</h5>
                            <h6 id="description">${t['description']}</h6>
                        </div>
                        <h6 id="occurance" class="border">${t['occurance_type'].toUpperCase()}</h6>
                    </div>
                    <!-- show excess only if applicable -->
                    <div id="excess">
                        <div id="details">
                            <h5>Enter excess:</h5>
                            <div id="actions">
                                <!-- replace with - and + svgs -->
                                <!-- <img> -->
                                <h5 onclick="incrementExcess(-1, '${s['ID']}')">-</h5>
                                <h5>${getParsedExcessCount(s)}</h5>
                                <h5 onclick="incrementExcess(1, '${s['ID']}')">+</h5>
                            </div>
                        </div>
                        <div id="points" class="border">
                            <h6>${totalPoints(s)}</h6>
                            <img src="./assets/leaf.svg">
                        </div>
                    </div>
                    <div id="instructions">
                        <!-- the (!) img icon here -->
                        <!-- <img> -->
                        <h6>
                            <!-- could use ::before element here -->
                            NOTE TO CURATORS :
                        </h6>
                        <h6>${t['curator_instructions']}</h6>
                    </div>
                </div>
                <div id="actions">
                    <!-- icons next to these? -->
                    <h4 class="border" id="reject" onclick="updateSubmissionStatus('rejected', '${s['ID']}')">
                        REJECT
                    </h4>
                    <h4 class="border" id="approve" onclick="updateSubmissionStatus('approved', '${s['ID']}')">
                        APPROVE
                    </h4>
                </div>
            </div>`;
        })
        container.innerHTML = result;

        renderSidebar();
    }

    function updateSubmissionStatus(status, submissionID) {
        fetch('../../api/submission/update.php?' + new URLSearchParams({
            'id': submissionID,
            'status': status,
            // TODO: replace with session storage
            'curator': 'curator1'
        }))
            .then((e) => e.text())
            .then((e) => {
                fetchData();
            })
    }
    window.updateSubmissionStatus = updateSubmissionStatus;

    function incrementExcess(amount, submissionID) {
        let action = submissions[submissionID]['action_count'];
        action = action == 0 ? tasks[submissions[submissionID]['task_ID']]['target'] : action;
        fetch('../../api/submission/update.php?' + new URLSearchParams({
            'id': submissionID,
            'action_count': action + amount,
            // TODO: replace with session storage
            'curator': 'curator1'
        }))
            .then((e) => e.text())
            .then((e) => {
                fetchData();
            })
    }
    window.incrementExcess = incrementExcess;


    changeQuery('');
    changeFilter('');
    fetchData();

    sidebarQueryInput.addEventListener('keyup', () => {
        changeQuery(sidebarQueryInput.value);
    });
})();