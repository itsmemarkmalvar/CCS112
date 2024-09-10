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

function login(event) {
    event.preventDefault(); // Prevent the form from submitting normally
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    // Here you would typically send these credentials to a server for verification
    // For this example, we'll just check if both fields are filled
    if (username && password) {
        // Redirect to the dashboard
        window.location.href = 'Dashboard.html';
    } else {
        alert('Please enter both username and password');
    }
}

// Add event listener when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', login);
    }
});
