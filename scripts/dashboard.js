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

// #region information card
const funFactContainer = document.querySelector("#fun-fact");
const funFactElement = funFactContainer.querySelector("#data h5");

(async () => {
    let payload = funFactContainer.getAttribute('data-payload');

    // professional prompt engineering :)
    await cohere_ask(
        `Below = total savings of this user. Generate a 40-60 worded passage about fun facts regarding it. Make it SUPER SUPER SUPER interesting as possible, include animals, technology, everyday habits/objects. Show rough conversions, X saved is equivalent to Y

        Use appropriate HTML tags (<strong>, etc) for font styling. Dont put it in a <p> tag. Dont use any Markdown elements. This is shown on app dashboard.
        
        <data>${payload}</data>`,
        (r) => {
            r = r.replaceAll('*', '');

            funFactElement.innerHTML = r;
            funFactContainer.setAttribute('data-state', 'data');
        },
        () => {
            console.log('failure');
            funFactContainer.setAttribute('data-state', 'failure');
        }
    );
})();
// #endregion