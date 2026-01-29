(function () { // IIFE idiom
    const dailyOverviewElement = document.querySelector("#page #content #schedule #overview #daily");
    const weeklyOverviewElement = document.querySelector("#page #content #schedule #overview #weekly");
    const selectorElement = document.querySelector("#page #content #schedule #selector #container");
    const sidebarElement = document.querySelector("#page #sidebar #schedule #container");
    var tasks = {};

    function fetchData() {
        fetch('/api/task/fetch_all.php?active=1')
            .then((e) => e.json())
            .then((e) => {
                tasks = e;

                render();
            });
    }

    let targetPoints = (task) => {
        return task["reward_rate"] * task["target"];
    }

    function getTasksFromDay(day) {
        return Object.values(tasks).filter((t) => (t['occurance_type'] == 'daily') && (((t["schedule"] >> day) & 1) == 1));
    }

    function getTasksFromWeek(week) {
        return Object.values(tasks).filter((t) => (t['occurance_type'] == 'weekly') && (t['schedule'] == week));
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

    function toggleWeek(task_id, week) {
        if (tasks[task_id]['schedule'] == week) {
            tasks[task_id]['schedule'] = 0;
        } else {
            tasks[task_id]['schedule'] = week;
        }

        fetch('/api/task/update.php?' + new URLSearchParams({
            id: task_id,
            schedule: tasks[task_id]['schedule']
        }).toString())
            .then((_) => {
                fetchData();
            });
    }
    window.toggleDay = toggleDay;
    window.toggleWeek = toggleWeek;
    // #endregion

    function render() {
        renderSelection();
        renderTimetable();
        renderSidebar();
    }

    function renderSidebar() {
        let days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        days.forEach((day, index) => {
            sidebarElement.querySelector(`#${day}`).innerHTML = getTasksFromDay(index).reduce((sum, task) => sum + targetPoints(task), 0);
        });

        let weeks = [
            [-1, "last-week"],
            [0, "this-week"],
            [1, "next-week"]
        ];

        weeks.forEach((week) => {
            sidebarElement.querySelector(`#${week[1]}`).innerHTML = getTasksFromWeek(getEpochWeek(new Date()) + week[0]).reduce((sum, task) => sum + targetPoints(task), 0);
        })
    }

    function renderSelection() {
        let result = '';
        Object.values(tasks).forEach((t) => {
            let scheduleContent = '';

            if (t['occurance_type'] == 'daily') {
                let days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
                getDayMap(t).forEach((m, index) => {
                    scheduleContent += `<h4 class='border' ${m ? 'data-active' : ''} onclick='toggleDay("${t['ID']}", ${index})'>${days[index]}</h4>`;
                });
            } else {
                let s = t['schedule'];

                scheduleContent = `
                <h4 class="border" ${(s == getEpochWeek(new Date())) ? 'data-active' : ''} onclick='toggleWeek("${t['ID']}", ${getEpochWeek(new Date())})'>THIS WEEK</h4>
                <h4 class="border" ${(s == getEpochWeek(new Date()) + 1) ? 'data-active' : ''} onclick='toggleWeek("${t['ID']}", ${getEpochWeek(new Date()) + 1})'>NEXT WEEK</h4>`;
            }

            result += `<div class='task-selector-card' data-occurance='${t['occurance_type']}'>
            <div id='data'>
                <span>
                    <h4>${t['title']}</h4>
                    <h5>${t['description']}</h5>
                </span>
                <div id='rewards' class='border'>
                    <h5>${targetPoints(t)}</h5>
                    <img src='./assets/leaf.svg'>
                </div>
            </div>
            <div id='schedule'>
                <hr>
                <div id='select'>${scheduleContent}</div>
            </div>
        </div>`;
        })
        selectorElement.innerHTML = result;
    }

    function renderTimetable() {
        let days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];
        let result = '';

        days.forEach((d, index) => {
            let content = '';
            getTasksFromDay(index).forEach((t) => {
                content += `<div class="task-minimal-card">
                <h5>${t['title']}</h5>
                    <div id="rewards" class="border">
                        <h5>${targetPoints(t)}</h5>
                        <img src="./assets/leaf.svg">
                    </div>
                </div>`;
            })

            result += `<div class='day' id='${d}'>
            <h3 id='title' class='border'>${d.toUpperCase()}</h3>
                <div id='container'>${content}</div>
            </div>`;

            if (index != 6) {
                result += "<hr>";
            }
        })

        dailyOverviewElement.innerHTML = result;

        let weeks = [[-1, 'last week'], [0, 'this week'], [1, 'next week']];
        result = '';
        weeks.forEach((w, index) => {
            let content = '';

            getTasksFromWeek(getEpochWeek(new Date()) + w[0]).forEach((e) => {
                content += `<div class="task-minimal-card">
                <h5>${e['title']}</h5>
                    <div id="rewards" class="border">
                        <h5>${targetPoints(e)}</h5>
                        <img src="./assets/leaf.svg">
                    </div>
                </div>`;
            });

            result += `<div class="week">
                <h3 class="border" id="title">${w[1].toUpperCase()}</h3>
                <div id="container">${content}</div>
            </div>`;

            if (index != 2) {
                result += "<hr>";
            }
        })

        weeklyOverviewElement.innerHTML = result;
    }

    fetchData();

})();

/*
'title'
'description'
'curator_instructions'

'active'

'target'
'excess_limit'
'reward_rate'

'schedule'

'goal_type'
'goal_contribution'

'occurance_type'
 */

