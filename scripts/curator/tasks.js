(function () { // IIFE idiom
    const container = document.querySelector("#page #content #tasks #container");
    var tasks = {};

    function fetchData() {
        fetch('/api/task/fetch_all.php')
            .then((e) => e.json())
            .then((e) => {
                tasks = e;

                render();
            })
    }

    let totalPoints = (task) => {
        return task["reward_rate"] * (task["target"] + task['excess_limit']);
    }

    function getDayMap(task) {
        let result = [];

        let d = task['schedule'];
        for (let i = 0; i < 7; i++) {
            result.push((d & 1) == 1);
            d >>= 1;
        }

        return result;
    }

    function render() {
        let result = '';
        Object.values(tasks).forEach((t) => {
            let dayContent = '';
            let days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
            getDayMap(t).forEach((m, index) => {
                dayContent += `<h4 class='border' ${m ? 'data-active' : ''} onclick='toggleDay("${t['ID']}", ${index})'>${days[index]}</h4>`;
            });

            result += `<div class="task-card-detailed" data-active="${t['active']}">
    <div id="header">
        <div id="data">
            <span>
                <h3>${t['title']}</h3>
                <div class="border" id="completion-rate">
                    <img src="./assets/completion_rate.svg">
                    <h5>50% completion rate</h5>
                </div>
            </span>
            <h5>${t['description']}</h5>
        </div>
        <div id="actions">
            <img class="border" src="./assets/edit.svg">
            <img class="border" src="./assets/trash.svg">
        </div>
    </div>
    <div id="holder">
        <div id="scheduled-days" class="border">
            <h5 class="box-title">SCHEDULED FOR</h5>
            <div id="days">
            ${dayContent}
            </div>
        </div>
        <div id="rewards">
            <div id="required" class="border">
                <div id="calculation">
                    <h6>${t['target']} x ${t['reward_rate']}</h6>
                    <img src="./assets/leaf.svg">
                </div>
                <div id="result">
                    <h3>${t['target'] * t['reward_rate']}</h3>
                    <img src="./assets/leaf.svg">
                </div>
                <h6 class="box-title">REQUIRED</h6>
            </div>
            <h3>+</h3>
            <div id="optional" class="border">
                <div id="calculation">
                    <h6>${t['excess_limit']} x ${t['reward_rate']}</h6>
                    <img src="./assets/leaf.svg">
                </div>
                <div id="result">
                    <h3>${t['excess_limit'] * t['reward_rate']}</h3>
                    <img src="./assets/leaf.svg">
                </div>
                <h6 class="box-title">OPTIONAL</h6>
            </div>
            <h3>=</h3>
            <div id="maximum" class="border">
                <div id="result">
                    <h3>${totalPoints(t)}</h3>
                    <img src="./assets/leaf.svg">
                </div>
                <h6 class="box-title">MAXIMUM</h6>
            </div>
        </div>
    </div >
    <div id="curator-note" class="border">
        <h5>${t['curator_instructions']}</h5>
    </div>
</div > `;
        })
        container.innerHTML = result;
    }

    // #region crud functions
    function toggleDay(task_id, day) {
        tasks[task_id]['schedule'] ^= (1 << day);

        fetch('/api/task/update.php?' + new URLSearchParams({
            id: task_id,
            schedule: tasks[task_id]['schedule']
        }).toString())
            .then((_) => {
                fetchData();
            });
    }
    window.toggleDay = toggleDay;
    // #endregion


    fetchData();
})();