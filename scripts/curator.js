// for dynamically loading scripts
var importElement = null;
var currentChild = null;

const sidebarElement = document.querySelector("#sidebar");

let imports = {
    'schedule': './scripts/curator/schedule.js',
    'tasks': './scripts/curator/tasks.js',
    'submissions': './scripts/curator/submissions.js',
}

function changeTab(t) {
    sidebarElement.setAttribute("data-tab", t);

    if (!importElement) {
        importElement = document.querySelector("#script-imports");
    }

    if (currentChild) {
        importElement.removeChild(currentChild);
        currentChild = null;
    }

    if (t in imports) {
        const script = document.createElement('script');
        script.src = imports[t] + '?t=' + Date.now(); // cache bust lol
        script.defer = true; // for iife
        importElement.appendChild(script);
        currentChild = script;
    }
}