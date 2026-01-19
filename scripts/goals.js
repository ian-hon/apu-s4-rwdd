// hamburger menu toggle
const hamburger = document.querySelector('#hamburger');

hamburger.addEventListener('click', () => {
    toggleNavbar();
});


// goal progress display
const personalBtn = document.querySelector('.Personal');
const globalBtn = document.querySelector('.Global');
const personalGoals = document.querySelector('.personal-goals');
const globalGoals = document.querySelector('.global-goals');

personalGoals.classList.add('active');
globalBtn.classList.add('active');   

personalBtn.addEventListener('click', () => {
    personalGoals.classList.add('active');
    globalGoals.classList.remove('active');

    personalBtn.classList.add('active');
    globalBtn.classList.remove('active');
});

globalBtn.addEventListener('click', () => {
    globalGoals.classList.add('active');
    personalGoals.classList.remove('active');

    globalBtn.classList.add('active');
    personalBtn.classList.remove('active');
});