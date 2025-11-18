// #region full dynamic height (https://stackoverflow.com/a/60229913)
function setProperVh() {
    // attempts to solve this problem (unsuccessful)
    // https://www.reddit.com/r/css/comments/1nk3uzp/full_viewport_height_on_ios_26/

    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}
window.addEventListener('resize', setProperVh);

setProperVh();
// #endregion

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
    countdown.innerHTML = getCountdownText(target - Date.now());
}

updateCountdown();

setInterval(() => {
    updateCountdown();
}, 300);
// #endregion

// #region navbar logic
const navbarElement = document.querySelector("#navbar");

function toggleNavbar() {
    navbarElement.setAttribute("data-active", navbarElement.getAttribute("data-active") == "true" ? "false" : "true");
}
// #endregion
