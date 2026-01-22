const navbarElement = document.querySelector("#navbar");

function toggleNavbar() {
    navbarElement.setAttribute("data-active", navbarElement.getAttribute("data-active") == "true" ? "false" : "true");
}