var duplicateUsername = false;

function validateForm() {
  const usernameInput = document.getElementById('username');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');
  const confirmPasswordInput = document.getElementById('confirm-password');
  
  const usernameError = document.getElementById('username-error');
  const duplicateUsernameError = document.getElementById('duplicate-username-error');
  const emailError = document.getElementById('email-error');
  const passwordError = document.getElementById('password-error');
  const confirmPasswordError = document.getElementById('confirm-password-error');
  
  let isValid = true; 
  
  // Validate username
  if (usernameInput.value.trim() === '') {
    usernameError.textContent = 'Please enter valid username';
    isValid = false;
  } else {
    usernameError.textContent = '';
  }

  if (duplicateUsername === true) { 
    duplicateUsernameError.textContent = 'Username already exists, please enter a different username';
    isValid = false;
  } else {
    duplicateUsernameError.textContent = '';
  }
  
  // Validate email
  if (emailInput.value.trim() === '') {
    emailError.textContent = 'Please enter valid email';
    isValid = false;
  } else {
    emailError.textContent = '';
  }
  
  // Validate password
  if (passwordInput.value.trim() === '') {
    passwordError.textContent = 'Please enter valid password';
    isValid = false;
  } else {
    passwordError.textContent = '';
  }
  
  // Validate confirm password
  if (confirmPasswordInput.value.trim() === '') {
    confirmPasswordError.textContent = 'Please confirm your password';
    isValid = false;
  } else if (passwordInput.value !== confirmPasswordInput.value) {
    confirmPasswordError.textContent = 'Passwords do not match';
    isValid = false;
  } else {
    confirmPasswordError.textContent = '';
  }
  
  return isValid;
}
