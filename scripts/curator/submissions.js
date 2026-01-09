(function () {
    const container = document.querySelector("#page #content #submissions #container");
    const lastUpdatedElement = document.querySelector("#page #content #submissions #header #last-updated");
    const submissionCountElement = document.querySelector("#page #content #submissions #header #submission-count");

    var submissions = {};
    var tasks = {};

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

    function render() {
        let result = '';
        Object.values(submissions).forEach((s) => {
            result += `<div class="submission-card" data-status="${s['status']}" data-occurance="${tasks[s['task_ID']]['occurance_type']}">
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
                            <h5 id="title">${tasks[s['task_ID']]['title']}</h5>
                            <h6 id="description">${tasks[s['task_ID']]['description']}</h6>
                        </div>
                        <h6 id="occurance" class="border">${tasks[s['task_ID']]['occurance_type'].toUpperCase()}</h6>
                    </div>
                    <!-- show excess only if applicable -->
                    <div id="excess">
                        <div id="details">
                            <h5>Enter excess:</h5>
                            <div id="actions">
                                <!-- replace with - and + svgs -->
                                <!-- <img> -->
                                <h5>-</h5>
                                <h5>${getParsedExcessCount(s)}</h5>
                                <h5>+</h5>
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
                        <h6>${tasks[s['task_ID']]['curator_instructions']}</h6>
                    </div>
                </div>
                <div id="actions">
                    <!-- icons next to these? -->
                    <h4 class="border" id="reject">
                        REJECT
                    </h4>
                    <h4 class="border" id="approve">
                        APPROVE
                    </h4>
                </div>
            </div>`;
        })
        container.innerHTML = result;
    }

    // #region crud functions
    // function toggleDay(task_id, day) {
    //     tasks[task_id]['schedule'] ^= (1 << day);

    //     fetch('/api/task/update.php?' + new URLSearchParams({
    //         id: task_id,
    //         schedule: tasks[task_id]['schedule']
    //     }).toString())
    //         .then((_) => {
    //             fetchData();
    //         });
    // }
    // window.toggleDay = toggleDay;
    // #endregion


    fetchData();
})();