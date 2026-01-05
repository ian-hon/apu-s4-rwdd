const sidebarElement = document.querySelector("#sidebar");
let currentFilter = '';

function changeTab(t) {
    sidebarElement.setAttribute("data-tab", t);
}

function changeFilter(f) {
    currentFilter = currentFilter == f ? '' : f;
    sidebarElement.setAttribute("data-filter", currentFilter);
}