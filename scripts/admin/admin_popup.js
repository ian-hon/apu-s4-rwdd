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
    if (parent) parent.classList.remove("blur"); 
}

const overlayEl = document.getElementById("overlay");
if (overlayEl) {
    overlayEl.addEventListener("click", function (e) {
        if (e.target === this) {
            closePopup();
        }
    });
}

function changeTab(pageId, event) {
  const pages = document.querySelectorAll('.page-content');
  pages.forEach(page => page.style.display = 'none');
  
  const activePage = document.getElementById(pageId);
  if (activePage) activePage.style.display = 'block';

  const buttons = document.querySelectorAll('.dash-btn');
  buttons.forEach(btn => btn.classList.remove('active'));
  event.currentTarget.classList.add('active');

  const filterDiv = document.getElementById('filter');
  if (pageId === 'stats') {
    filterDiv.style.display = 'none';
  } else {
    filterDiv.style.display = 'block'; 
  }
}