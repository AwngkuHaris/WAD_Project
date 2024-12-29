
document.addEventListener('DOMContentLoaded', () => {
    const filterInput = document.getElementById('filterInput');
    const serviceCards = document.querySelectorAll('.service-card');

    filterInput.addEventListener('input', () => {
        const filterText = filterInput.value.toLowerCase();

        serviceCards.forEach(card => {
            const serviceName = card.querySelector('h2').textContent.toLowerCase();

            if (serviceName.startsWith(filterText)) {
                card.style.display = ''; // Show the card
            } else {
                card.style.display = 'none'; // Hide the card
            }
        });
    });
});

