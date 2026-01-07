var importElement = null;
const sidebarElement = document.querySelector("#sidebar");
let currentFilter = '';

let imports = {
    'schedule': '<script src="./scripts/curator/schedule.js"></script>',
}

function changeTab(t) {
    sidebarElement.setAttribute("data-tab", t);

    if (!importElement) {
        importElement = document.querySelector("#script-imports");
    }

    if (t in imports) {
        importElement.innerHTML = imports[t];
    } else {
        importElement.innerHTML = '';
    }
}

function changeFilter(f) {
    currentFilter = currentFilter == f ? '' : f;
    sidebarElement.setAttribute("data-filter", currentFilter);
}