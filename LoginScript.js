document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM Content Loaded");
    
    const toggleLinks = document.querySelectorAll('.form-toggle');
    toggleLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const targetFormId = this.getAttribute('data-target');
            toggleForm(targetFormId);
        });
    });
    
    // Add event listener for register form submission
    const registerForm = document.getElementById('registerForm').querySelector('form');
    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        submitRegisterForm();
    });

    // Add event listener for login form submission
    const loginForm = document.getElementById('loginForm').querySelector('form');
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault();
        submitLoginForm();
    });
});

function toggleForm(formId) {
    console.log("Toggling to form:", formId);
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const container = document.querySelector('.container');
    
    if (formId === 'registerForm') {
        loginForm.classList.add('hidden');
        registerForm.classList.add('visible');
    } else {
        loginForm.classList.remove('hidden');
        registerForm.classList.remove('visible');
    }
    
    // Delay the height adjustment to allow for the transition
    setTimeout(() => {
        const activeForm = formId === 'registerForm' ? registerForm : loginForm;
        container.style.height = `${activeForm.offsetHeight}px`;
    }, 50);
}

function togglePassword(icon) {
    const input = icon.previousElementSibling;
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

// Initial height set
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.container');
    const loginForm = document.getElementById('loginForm');
    container.style.height = `${loginForm.offsetHeight}px`;
});

function submitRegisterForm() {
    const form = document.getElementById('registerForm').querySelector('form');
    const formData = new FormData(form);

    fetch('Register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result === "Registration successful") {
            alert("Registration successful! You can now log in.");
            toggleForm('loginForm');
            form.reset();
        } else if (result === "Username already exists") {
            alert("This username is already taken. Please choose a different username.");
        } else {
            alert(result);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred. Please try again.");
    });
}

function submitLoginForm() {
    const form = document.getElementById('loginForm').querySelector('form');
    const formData = new FormData(form);

    fetch('Login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.text();
    })
    .then(text => {
        console.log('Raw server response:', text);
        if (text.trim() === '') {
            throw new Error('Empty response from server');
        }
        const data = JSON.parse(text);
        console.log('Parsed JSON data:', data);
        if (data.status === "success") {
            console.log('Redirecting to Dashboard.php');
            window.location.href = 'Dashboard.php';
        } else {
            console.log('Login failed:', data.message);
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Fetch Error:', error);
        alert("An error occurred. Please try again.");
    });
}

function logout() {
    fetch('logout.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            console.log('Logged out successfully');
            window.location.href = 'LoginForm.html'; // Redirect to login page
        } else {
            console.log('Logout failed:', data.message);
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("An error occurred during logout. Please try again.");
    });
}
