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
    let now = target - Date.now();
    countdown.innerHTML = getCountdownText(now);
}

updateCountdown();

setInterval(() => {
    updateCountdown();
}, 300);