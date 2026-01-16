const hamburger = document.querySelector('.hamburger');
const menulink = document.querySelector('.menu-link');

hamburger.addEventListener('click', () => {
    hamburger.classList.toggle();
    menulink.classList.toggle();
})

menulink.array.forEach(link => {
    link.addEventListener('click', () => {
        hamburger.classList.remove();
        menulink.classList.remove();
    })

});