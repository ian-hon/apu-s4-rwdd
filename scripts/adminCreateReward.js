function openPopup() {
    const overlay = document.getElementById("overlay");
    const parent = document.getElementById("parent");
    
    if (overlay) overlay.classList.add("active");
    if (parent) parent.classList.add("blur");
}

function closePopup() {
    const overlay = document.getElementById("overlay");
    const parent = document.getElementById("parent");

    if (overlay) overlay.classList.remove("active");
<<<<<<< Updated upstream
    if (parent) parent.classList.remove("blur"); 
}

=======
    if (parent) parent.classList.remove("blur"); // Match the ID used in openPopup
}

// Wrap the listener in a check to prevent "null" errors
>>>>>>> Stashed changes
const overlayEl = document.getElementById("overlay");
if (overlayEl) {
    overlayEl.addEventListener("click", function (e) {
        if (e.target === this) {
            closePopup();
        }
    });
}

function changeTab(pageId) {
<<<<<<< Updated upstream
  
=======
  // 1. Hide all elements with the class 'page-content'
>>>>>>> Stashed changes
  const pages = document.querySelectorAll('.page-content');
  pages.forEach(page => {
    page.style.display = 'none';
  });

<<<<<<< Updated upstream
=======
  // 2. Show the specific page that was clicked
>>>>>>> Stashed changes
  document.getElementById(pageId).style.display = 'block';
}

function changeTab(pageId, event) {
<<<<<<< Updated upstream
=======
  // 1. Switch the pages (Your existing logic)
>>>>>>> Stashed changes
  const pages = document.querySelectorAll('.page-content');
  pages.forEach(page => page.style.display = 'none');
  document.getElementById(pageId).style.display = 'block';

<<<<<<< Updated upstream
  const buttons = document.querySelectorAll('.buttons');
  buttons.forEach(btn => btn.classList.remove('active'));

=======
  // 2. Switch the button colors
  // Remove 'active' class from all buttons
  const buttons = document.querySelectorAll('.buttons');
  buttons.forEach(btn => btn.classList.remove('active'));

  // Add 'active' class to the button that was clicked
>>>>>>> Stashed changes
  event.currentTarget.classList.add('active');
}