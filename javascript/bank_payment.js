const tabs = document.querySelectorAll('.tab');
const paymentModal = document.querySelector('.payment-container');
const payButtons = document.querySelectorAll('.pay-btn');
const closeModalButton = document.getElementById('close-modal');

// Handle tab switching
tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        // Remove 'active' class from all tabs
        tabs.forEach(t => t.classList.remove('active'));

        // Hide all sections by default
        const sections = document.querySelectorAll('.payment-section');
        sections.forEach(section => section.style.display = 'none');

        // Add 'active' class to the clicked tab and show the relevant section
        tab.classList.add('active');
        if (tab.id === 'card-tab') {
            document.getElementById('card-section').style.display = 'block';
        } else if (tab.id === 'online-banking-tab') {
            document.getElementById('online-banking-section').style.display = 'block';
        }
    });
});

// Initialize default view (Card Payment Section is shown by default)
document.getElementById('card-section').style.display = 'block';

// Show the modal when "Pay Now" is clicked
payButtons.forEach(button => {
    button.addEventListener('click', () => {
        const paymentId = button.closest('form').querySelector('input[name="payment_id"]').value;
        document.getElementById('payment-id').value = paymentId; // For card payment
        document.getElementById('payment-id-banking').value = paymentId; // For online banking
        paymentModal.style.display = 'block'; // Show modal
    });
});

// Hide the modal when the close button is clicked
closeModalButton.addEventListener('click', () => {
    paymentModal.style.display = 'none'; // Hide modal
});

// Optional: Close the modal if the user clicks outside the modal content
window.addEventListener('click', (event) => {
    if (event.target === paymentModal) {
        paymentModal.style.display = 'none';
    }
});
