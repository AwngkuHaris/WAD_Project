document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const errorMessage = document.getElementById('error-message');

    loginForm.addEventListener('submit', (event) => {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();

        // Validate Email
        if (!email) {
            event.preventDefault();
            errorMessage.textContent = "Please enter your email.";
            errorMessage.style.display = "block";
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            event.preventDefault();
            errorMessage.textContent = "Please enter a valid email address.";
            errorMessage.style.display = "block";
            return;
        }

        // Validate Password
        if (!password) {
            event.preventDefault();
            errorMessage.textContent = "Please enter your password.";
            errorMessage.style.display = "block";
            return;
        }

        // Clear any previous error messages
        errorMessage.style.display = "none";
    });
});
