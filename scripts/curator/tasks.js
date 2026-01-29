(function () { // IIFE idiom
    const container = document.querySelector("#page #content #tasks #container");
    const deletePopup = document.querySelector("#page #content #tasks #confirmation-popup");
    var tasks = {};
    var completionRates = {};

    function fetchData() {
        fetch('/api/task/fetch_all.php?active=1')
            .then((e) => e.json())
            .then((e) => {
                fetch('/api/task/helper.php')
                    .then((h) => h.json())
                    .then((h) => {
                        completionRates = h;
                        tasks = e;

                        document.querySelector("#task-count").innerHTML = `Create and manage all tasks (${Object.keys(tasks).length} available)`;

                        render();
                    })
            })
    }

    function render() {
        fetch('/scripts/curator/task_renderer.php')
            .then((e) => e.text())
            .then((e) => {
                container.innerHTML = e;
            })
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
    window.toggleWeek = toggleWeek;
    // #endregion

    // #region confirmation popup
    var toBeDeleted = '';
    deletePopup.querySelector("input").addEventListener('keyup', () => {
        deletePopup.setAttribute('data-valid', `delete ${tasks[toBeDeleted]['title']}` == deletePopup.querySelector("input").value);
    })

    function toggleDeletePopup(state) {
        deletePopup.setAttribute("data-active", state);
    }
    window.toggleDeletePopup = toggleDeletePopup;

    function askDeleteTask(task_id) {
        toBeDeleted = task_id;
        deletePopup.querySelector("input").value = '';
        deletePopup.setAttribute('data-valid', false);
        deletePopup.querySelector("#instruction").innerHTML = `Enter 'delete ${tasks[task_id]['title']}' into the box below`;

        deletePopup.setAttribute("data-active", 'true');
    }
    window.askDeleteTask = askDeleteTask;

    function deleteTask() {
        if (`delete ${tasks[toBeDeleted]['title']}` == deletePopup.querySelector("input").value) {
            fetch('/api/task/update.php?' + new URLSearchParams({
                id: toBeDeleted,
                active: 0
            }).toString())
                .then((e) => e.text())
                .then((e) => {
                    console.log(e);
                    toggleDeletePopup(false);
                    fetchData();
                });
        }
    }
    window.deleteTask = deleteTask;
    // #endregion


    fetchData();
})();