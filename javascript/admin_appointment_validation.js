
document.querySelector('.appointment-form').addEventListener('submit', function(event) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const date = document.getElementById('appointment_date').value.trim();
    const time = document.getElementById('time').value.trim();
    const service = document.getElementById('service_id').value.trim();

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^[0-9]{10,15}$/;

    let isValid = true;
    let errorMessage = '';

    if (name === '') {
        errorMessage += 'Name is required.\n';
        isValid = false;
    }

    if (!emailRegex.test(email)) {
        errorMessage += 'Enter a valid email address.\n';
        isValid = false;
    }

    if (!phoneRegex.test(phone)) {
        errorMessage += 'Phone number must be between 10 and 15 digits.\n';
        isValid = false;
    }

    if (date === '') {
        errorMessage += 'Appointment date is required.\n';
        isValid = false;
    }

    if (time === '') {
        errorMessage += 'Appointment time is required.\n';
        isValid = false;
    }

    if (service === '') {
        errorMessage += 'Please select a service.\n';
        isValid = false;
    }

    if (!isValid) {
        alert(errorMessage);
        event.preventDefault(); // Stop form submission
    }
});

