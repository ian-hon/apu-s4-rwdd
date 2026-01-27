const filterElement = document.querySelector("#filters");

function toggleFilter(filter) {
    filterElement.setAttribute('data-filter', filter);
}

// #region dummy timer at task section
const countdown = document.querySelector(".countdown");
const target = (Date.now() - (Date.now() % 86400_000)) + 86400_000;

function getCountdownText(seconds) {
    seconds /= 1000;
    seconds = Math.floor(seconds);

    let minutes = Math.floor(seconds / 60);
    let hours = Math.floor(minutes / 60);

    seconds = seconds % 60;
    minutes %= 60;

    return `${hours}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

function updateCountdown() {
    countdown.innerHTML = `resets in ${getCountdownText(target - Date.now())}`;
}

updateCountdown();

setInterval(() => {
    updateCountdown();
}, 300);
// #endregion