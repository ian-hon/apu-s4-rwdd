const overviewElement = document.querySelector("#page #content #overview");
const selectorElement = document.querySelector("#page #content #selector #container");
var tasks = {};

fetch('/api/task/fetch_all.php')
    .then((e) => e.json())
    .then((e) => {
        tasks = e;
        // tasks = Object.values(e);

        render();
    });

let totalPoints = (task) => {
    return task["reward_rate"] * task["target"];
}

function getTasksFromDay(day) {
    return Object.values(tasks).filter((t) => ((t["schedule"] >> day) & 1) == 1)
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

    render();

    // fetch('/api/task/update.php',)
}
// #endregion

function render() {
    renderSelection();
    renderTimetable();
}

function renderSelection() {
    let result = '';
    Object.values(tasks).forEach((t) => {
        let dayContent = '';
        let days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
        getDayMap(t).forEach((m, index) => {
            dayContent += `<h4 class='border' ${m ? 'data-active' : ''} onclick='toggleDay("${t['ID']}", ${index})'>${days[index]}</h4>`;
        });

        result += `<div class='task-selector-card'>
            <div id='data'>
                <span>
                    <h4>${t['title']}</h4>
                    <h5>${t['description']}</h5>
                </span>
                <div id='rewards' class='border'>
                    <h5>${totalPoints(t)}</h5>
                    <img src='./assets/leaf.svg'>
                </div>
            </div>
            <div id='schedule'>
                <hr>
                <div id='select'>${dayContent}</div>
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
                        <h5>${totalPoints(t)}</h5>
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

    overviewElement.innerHTML = result;
}

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

