// Password Generator

function passwordGenerator(passwordLength, includeLowerCase, includeUpperCase, includeNumber, includeSymbols) {
    const lowerCaseChars = 'abcdefghijklmnopqrstuvwxyz';
    const upperCaseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numberChars = '0123456789';
    const symbolChars = '!@#$%^&*()_+-=';

    let allowedChars = '';
    let password = '';

    allowedChars += includeLowerCase ? lowerCaseChars : '';
    allowedChars += includeUpperCase ? upperCaseChars : '';
    allowedChars += includeNumber ? numberChars : '';
    allowedChars += includeSymbols ? symbolChars : '';

    if (passwordLength <= 0) {
        return `(Passoword length must be at least 1)`;
    }
    if (allowedChars.length === 0) {
        return `(At least 1 set of charector needs to be selected)`;
    }

    for (let i = 0; i < passwordLength; i++) {
        const randomIndex = Math.floor(Math.random() * allowedChars.length)
        password += allowedChars[randomIndex];
    }

    return password;
}

// Replace pass with new random pass
const newPass = document.getElementById('newPass');
const confirmPass = document.getElementById('confirmPass');
const randomPassBtn = document.getElementById('randomPass');

randomPassBtn.addEventListener('click', (e) => {
    e.preventDefault(); //Prevent form submission

    // calling the generate random pass function
    const passwordLength = 12;
    const includeLowerCase = true;
    const includeUpperCase = true;
    const includeNumber = true;
    const includeSymbols = true;

    const password = passwordGenerator(passwordLength, includeLowerCase, includeUpperCase, includeNumber, includeSymbols);

    newPass.value = password;
    confirmPass.value = password;

    alert(`Your password is : ${password}
Do not share this password and stored it somewhere safely!`);
})