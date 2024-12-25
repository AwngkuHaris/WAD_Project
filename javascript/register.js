// Select the form and response message element
const form = document.getElementById('registerForm');
const message = document.getElementById('responseMessage');

// Handle form submission
form.addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevent default form submission

    // Prepare form data
    const formData = new FormData(form);

    try {
        // Send data to the backend
        const response = await fetch('backend/register.php', {
            method: 'POST',
            body: formData,
        });

        // Parse JSON response
        const result = await response.json();

        // Display the response message
        if (result.success) {
            message.textContent = result.message;
            message.style.color = 'green';
            form.reset(); // Reset the form on success
        } else {
            message.textContent = result.message;
            message.style.color = 'red';
        }
    } catch (error) {
        message.textContent = 'An error occurred. Please try again.';
        message.style.color = 'red';
    }
});
