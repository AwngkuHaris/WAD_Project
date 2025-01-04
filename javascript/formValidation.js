// Function to check if the email is unique
async function checkEmailExists(email) {
    try {
        const response = await fetch('../../backend/check_email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email }),
        });
        const result = await response.json();

        if (result.status === 'error' && result.message === 'Email already exists') {
            document.getElementById('email-duplicate-error').textContent = 'This email is  registered.';
            return false;
        }

        document.getElementById('email-duplicate-error').textContent = '';
        return true;
    } catch (error) {
        console.error('Error checking email:', error);
        document.getElementById('email-duplicate-error').textContent = 'Error checking email. Please try again.';
        return false;
    }
}

// Set the maximum date for the date input field to today's date (YYYY-MM-DD format)
function setMaxDateForDateOfBirth() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
    const day = String(today.getDate()).padStart(2, '0');
    const formattedToday = `${year}-${month}-${day}`;
    document.getElementById('dateOfBirth').setAttribute('max', formattedToday);
}


async function validateForm(event) {
    // Prevent default form submission
    event.preventDefault();

    const fullName = document.getElementById('fullName').value.trim();
    const myKadNumber = document.getElementById('myKadNumber').value.trim();
    const dateOfBirth = document.getElementById('dateOfBirth').value.trim();
    const contactNumber = document.getElementById('contactNumber').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const gender = document.querySelector('input[name="gender"]:checked');
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    const postcode = document.getElementById('postcode').value.trim();
    const country = document.getElementById('country').value.trim();
    const terms = document.getElementById('terms').checked;

    let isValid = true;

    // Clear previous errors
    document.querySelectorAll('.error-message').forEach((el) => (el.textContent = ''));

    // Validate full name
    if (fullName === '' || !/^[A-Za-z\s]+$/.test(fullName)) {
        document.getElementById('fullName-error').textContent = 'Please enter your full name using alphabets only.';
        isValid = false;
    }

    // Validate MyKad number
    if (myKadNumber === '' || !/^\d{6}-\d{2}-\d{4}$/.test(myKadNumber)) {
        document.getElementById('myKadNumber-error').textContent = 'Please enter a valid MyKad number (xxxxxx-xx-xxxx).';
        isValid = false;
    }

    // Validate date of birth
    if (dateOfBirth === '') {
        document.getElementById('dateOfBirth-error').textContent = 'Please select your date of birth.';
        isValid = false;
    }

    // Validate contact number
    if (contactNumber === '' || !/^\+?\d+$/.test(contactNumber)) {
        document.getElementById('contactNumber-error').textContent = 'Please enter a valid contact number.';
        isValid = false;
    }

    // Validate email format
    if (email === '' || !/\S+@\S+\.\S+/.test(email)) {
        document.getElementById('email-error').textContent = 'Please enter a valid email address.';
        isValid = false;
    }

    // Check email uniqueness
    isEmailUnique = await checkEmailExists(email);
    if (!isEmailUnique) {
        isValid = false;
    }

    // Validate password
    if (
        password === '' ||
        !/^(?=.*[A-Z])(?=.*\d)(?=.*[$&+,:;=?@#|'<>.^*()%!-])[A-Za-z\d$&+,:;=?@#|'<>.^*()%!-]{6,8}$/.test(password)
    ) {
        document.getElementById('password-error').textContent =
            'Password must be 6-8 characters long with one uppercase letter, one number, and one special character.';
        isValid = false;
    }

    // Validate gender
    if (!gender) {
        document.getElementById('gender-error').textContent = 'Please select your gender.';
        isValid = false;
    }

    // Validate address
    if (address === '') {
        document.getElementById('address-error').textContent = 'Please enter your address.';
        isValid = false;
    }

    // Validate city
    if (city === '') {
        document.getElementById('city-error').textContent = 'Please enter your city.';
        isValid = false;
    }

    // Validate postcode
    if (postcode === '' || !/^\d{5}$/.test(postcode)) {
        document.getElementById('postcode-error').textContent = 'Please enter a valid 5-digit postcode.';
        isValid = false;
    }

    // Validate country
    if (country === '') {
        document.getElementById('country-error').textContent = 'Please select your country.';
        isValid = false;
    }

    // Validate terms agreement
    if (!terms) {
        document.getElementById('terms-error').textContent = 'You must agree to the privacy policy.';
        isValid = false;
    }

    // If all validations pass, submit the form
    if (isValid) {
        alert('You have successfully registered for the membership!');
        document.getElementById('registrationForm').submit();
    }
}

window.onload = () => {
    setMaxDateForDateOfBirth(); // Set the max date for date of birth

    // Add blur event listener to the email field
    const emailField = document.getElementById('email');
    emailField.addEventListener('blur', async (e) => {
        const email = e.target.value.trim(); // Get the entered email
        if (email) {
            await checkEmailExists(email); // Call the async function to check email
        }
    });
};

