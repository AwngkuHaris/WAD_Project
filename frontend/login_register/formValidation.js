/*source: reference from google with some modification*/


function validateForm() {
    const fullName = document.getElementById("fullName").value;
    const myKadNumber = document.getElementById("myKadNumber").value;
    const dateOfBirth = document.getElementById("dateOfBirth").value;
    const contactNumber = document.getElementById("contactNumber").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const gender = document.querySelector('input[name="gender"]:checked');
    const address = document.getElementById("address").value;
    const city = document.getElementById("city").value;
    const postcode = document.getElementById("postcode").value;
    const country = document.getElementById("country").value;
    const terms = document.getElementById("terms").checked;

    // Error Elements
    const errors = {
        fullNameError: "Please enter your full name in ALPHABETS  only).",
        myKadNumberError: "Please enter a valid MyKad number in this format: xxxxxx-xx-xxxx.",
        dateOfBirthError: "Please select your date of birth.",
        contactNumberError: "Please enter a valid contact number.",
        emailError: "Please enter a valid email address.",
        passwordError: "Password must be 6-8 characters long, which contain ONE uppercase, ONE numeric, ONE special character, number and NO space.",
        genderError: "Please select your gender.",
        addressError: "Please enter your address.",
        cityError: "Please enter your city.",
        postcodeError: "Please enter 5 digit postcode.",
        countryError: "Please select your country.",
        termsError: "You must agree to the privacy policy in order to proceed."
    };

    // Clear previous Errors
    for (const errorElement in errors) {
        const element = document.getElementById(errorElement);
        if (element) element.textContent = "";
    }

    let isValid = true;

    // Validation of form 
    if (fullName === "" || !/^[A-Za-z\s]+$/.test(fullName)) {
        document.getElementById("fullName-error").textContent = errors.fullNameError;
        isValid = false;
    }

    if (myKadNumber === "" || !/^\d{6}-\d{2}-\d{4}$/.test(myKadNumber)) {
        document.getElementById("myKadNumber-error").textContent = errors.myKadNumberError;
        isValid = false;
    }

    if (dateOfBirth === "") {
        document.getElementById("dateOfBirth-error").textContent = errors.dateOfBirthError;
        isValid = false;
    }

    if (contactNumber === "" || !/^\+?\d+$/.test(contactNumber)) {
        document.getElementById("contactNumber-error").textContent = errors.contactNumberError;
        isValid = false;
    }

    if (email === "" || !/\S+@\S+\.\S+/.test(email)) {
        document.getElementById("email-error").textContent = errors.emailError;
        isValid = false;
    }

    if (
        password === "" ||
        !/^(?=.*[A-Z])(?=.*\d)(?=.*[$&+,:;=?@#|'<>.^*()%!-])[A-Za-z\d$&+,:;=?@#|'<>.^*()%!-]{6,8}$/.test(password)
    ) {
        document.getElementById("password-error").textContent = errors.passwordError;
        isValid = false;
    }

    if (!gender) {
        document.getElementById("gender-error").textContent = errors.genderError;
        isValid = false;
    }

    if (address === "") {
        document.getElementById("address-error").textContent = errors.addressError;
        isValid = false;
    }

    if (city === "") {
        document.getElementById("city-error").textContent = errors.cityError;
        isValid = false;
    }

    if (postcode === "" || !/^\d{5}$/.test(postcode)) {
        document.getElementById("postcode-error").textContent = errors.postcodeError;
        isValid = false;
    }

    if (country === "") {
        document.getElementById("country-error").textContent = errors.countryError;
        isValid = false;
    }

    if (!terms) {
        document.getElementById("terms-error").textContent = errors.termsError;
        isValid = false;
    }

    if(isValid){
        alert("You have successfully registered for the membership !");
    }

    return isValid;
}

 // Function to check the date and prevent user from selecting the future date 
 function isValidDateOfBirth(dateOfBirth) {
    const today = new Date();
    const dob = new Date(dateOfBirth);
    return dob <= today;
}

// Set the maximum date for the date input field to today's date (YYYY-MM-DD format)
function setMaxDateForDateOfBirth() {
    const today = new Date();
    const formattedToday = today.toISOString().split('T')[0];  // Convert to YYYY-MM-DD format
    document.getElementById("dateOfBirth").setAttribute("max", formattedToday);
}

// Call the function to set the max date when the page loads
window.onload = setMaxDateForDateOfBirth;

