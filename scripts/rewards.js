const hamburger = document.querySelector('#hamburger');
const redeemButtons = document.querySelectorAll('.redeem-btn');
const redeemPopup = document.querySelector('#redeemed-popup');
const continueBtn = document.querySelector('#continue-btn');
const historyBtn = document.querySelector('#history-btn');

hamburger.addEventListener('click', () => {
    toggleNavbar();
});

redeemButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        if (!button.disabled) {
            redeemPopup.style.display = 'flex';
        }
    });
});

continueBtn.addEventListener('click', () => {
    redeemPopup.style.display = 'none';
});

historyBtn.addEventListener('click', () => {
    window.location.href = './redemption_history.php';
});

redeemPopup.addEventListener('click', (e) => {
    if (e.target === redeemPopup) {
        redeemPopup.style.display = 'none';
    }
});