<div id="tasks">
    <div id="header">
        <div id="text">
            <h2>TASK CATALOGUE</h2>
            <h5 id="task-count"></h5>
        </div>
        <a href="./curator/create_task.php" id="action" class="border">
            <h3>+</h3>
            <h5>CREATE TASK</h5>
        </a>
    </div>
    <div id="container">
    </div>
    <div id="confirmation-popup" data-active='false'>
        <div id="delete" class="border">
            <h2 id="title">Are you sure?</h2>
            <h4>This action cannot be undone!</h4>
            <hr>
            <h5 id="instruction">Enter 'delete something something' into the box below</h5>
            <input></input>
            <div id="actions">
                <h4 class="border" id="cancel" onclick="toggleDeletePopup('false')">CANCEL</h4>
                <h4 class="border" id="delete" onclick="deleteTask()">DELETE</h4>
            </div>
        </div>
    </div>
</div>