function validateForm() {
  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  
  const usernameError = document.getElementById('username-error');
  const passwordError = document.getElementById('password-error');
  
  let isValid = true;
  
  // Validate username
  if (usernameInput.value.trim() === '') {
    usernameError.textContent = 'Please enter valid username';
    isValid = false;
  } else {
    usernameError.textContent = '';
  }
  
  // Validate password
  if (passwordInput.value.trim() === '') {
    passwordError.textContent = 'Please enter valid password';
    isValid = false;
  } else {
    passwordError.textContent = '';
  }
  
  return isValid;
}
