(function () {
    const container = document.querySelector("#page #content #submissions #container");
    const lastUpdatedElement = document.querySelector("#page #content #submissions #header #last-updated");
    const submissionCountElement = document.querySelector("#page #content #submissions #header #submission-count");

    const sidebar = document.querySelector("#page #sidebar #sidebar-content #submissions #overview");
    const sidebarPie = sidebar.querySelector("#pie");
    const sidebarStatistics = sidebar.querySelector("#statistics");

    const sidebarQueryInput = document.querySelector("#page #sidebar #sidebar-content #submissions #query #search input");

    var username = '';

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

                        lastUpdatedElement.innerHTML = `last updated at ${(new Date()).toString()}`;
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

    function renderSidebar() {
        let approved = parseInt((ratios.approved / Object.keys(submissions).length) * 100_000);
        let rejected = parseInt((ratios.rejected / Object.keys(submissions).length) * 100_000);
        let pending = 100_000 - approved - rejected;

        sidebarStatistics.querySelector("#pending").innerHTML = `<h5>${ratios.pending} PENDING</h5><h5>${parseInt(pending / 1_000)}%</h5>`;
        sidebarStatistics.querySelector("#approved").innerHTML = `<h5>${ratios.approved} APPROVED</h5><h5>${parseInt(approved / 1_000)}%</h5>`;
        sidebarStatistics.querySelector("#rejected").innerHTML = `<h5>${ratios.rejected} REJECTED</h5><h5>${parseInt(rejected / 1_000)}%</h5>`;

        approved *= 3.60 / 1_000;
        rejected *= 3.60 / 1_000;
        pending *= 3.60 / 1_000;

        // rejected, approved, pending
        sidebarPie.innerHTML = `<div id="rejected" style="background: conic-gradient(var(--error) ${rejected}deg, transparent ${rejected}deg)"></div>
        <div id="approved" style="background: conic-gradient(transparent ${rejected}deg, var(--accent) ${rejected}deg ${approved + rejected}deg, transparent ${approved + rejected}deg)"></div>
        <div id="pending" style="background: conic-gradient(transparent ${rejected + approved}deg, var(--pending) ${rejected + approved}deg 360deg, transparent 360deg)"></div>`;
    }

    function render() {
        let u = new URLSearchParams({});
        if (currentFilter.length != 0) {
            u.append('status', currentFilter);
        }

        if (currentQuery.length != 0) {
            u.append('query', currentQuery);
        }

        fetch('/scripts/curator/submissions_renderer.php?' + u)
            .then((e) => e.text())
            .then((e) => {
                container.innerHTML = e;

                renderSidebar();
            })
    }

    function updateSubmissionStatus(status, submissionID) {
        fetch('../../api/submission/update.php?' + new URLSearchParams({
            'id': submissionID,
            'status': status,
            'curator': window.username
        }))
            .then((e) => e.text())
            .then((e) => {
                fetchData();
            })
    }
    window.updateSubmissionStatus = updateSubmissionStatus;

    function incrementExcess(amount, submissionID) {
        let action = submissions[submissionID]['action_count'];
        fetch('../../api/submission/update.php?' + new URLSearchParams({
            'id': submissionID,
            'action_count': action + amount,
            'curator': window.username
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