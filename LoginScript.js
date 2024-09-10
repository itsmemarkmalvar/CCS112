function toggleForm() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm.classList.contains('slide-left')) {
        loginForm.classList.remove('slide-left');
        registerForm.classList.remove('slide-right');
    } else {
        loginForm.classList.add('slide-left');
        registerForm.classList.add('slide-right');
    }
}

function register(event) {
    event.preventDefault();
    
    const form = document.getElementById('registrationForm');
    const formData = new FormData(form);

    fetch('Register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        console.log('Server response:', result);
        if (result.trim() === "Registration successful") {
            alert("Registration successful!");
            form.reset(); // Clear the registration form
            toggleForm(); // Switch back to login form
        } else {
            alert(result || 'An unexpected error occurred.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during registration: ' + error.message);
    });
}

// Add this event listener to ensure the function is connected to the form
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registrationForm');
    if (registerForm) {
        registerForm.addEventListener('submit', register);
    }
});

