// Camera access prompt
const cameraBtn = document.querySelector("#cameraBtn");
const camera = document.querySelector("#camera");
const cancel = document.querySelector("#cancelBtn");
const submit = document.querySelector("#submitBtn");

cameraBtn.addEventListener('click', (e) => {
    e.preventDefault(); //prevent default action 
    camera.classList.add('active');
    // cameraInput.click();
});

cancel.addEventListener('click', (e) => {
    e.preventDefault(); //prevent navigation
    camera.classList.remove('active');

    actualPicture.innerHTML = `<img src="./assets/ivp/camera-svgrepo-com.svg" alt="">`;

    // clear file input (user select can again)
    cameraInput.value = '';
});

// submit.addEventListener('click', () => {
//     // alert('Image successfully submitted!')
// })

// Access mobile camera
const cameraInput = document.getElementById('submission-image');
const actualPicture = document.getElementById('actual-picture');

// (e) parameter = variable name we choose to hold the event data 
cameraInput.addEventListener('change', (e) => {
    const file = e.target.files[0]; //first file from the list (array starts with 0)

    if (file) {
        const reader = new FileReader();

        reader.onload = (event) => {
            actualPicture.innerHTML = `
            <img src='${event.target.result}' style='width:100%'; height:100%; object-fit:cover;'>`
        };

        reader.readAsDataURL(file);
    }
});