const hamburger = document.querySelector('#hamburger');

hamburger.addEventListener('click', () => {
    toggleNavbar();
});

// Profile picture upload
const pfpUpload = document.querySelector('#pfp-upload');
const pfpForm = document.querySelector('#pfp-form');

if (pfpUpload && pfpForm) {
    pfpUpload.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            pfpForm.submit();
        }
    });
}