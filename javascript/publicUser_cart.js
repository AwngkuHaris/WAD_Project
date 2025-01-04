/* javascipt to update the total price in the cart of public user*/

document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.qty');
    const totalPriceElement = document.getElementById('total-price');

    function calculateTotal() {
        let total = 0;

        quantityInputs.forEach(input => {
            const price = parseFloat(input.closest('tr').querySelector('td[data-price]').getAttribute('data-price'));
            const quantity = parseInt(input.value);
            total += price * quantity;
        });

        totalPriceElement.textContent = 'RM' + total; // Update total price display
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', calculateTotal); // Listen for input changes
    });

    calculateTotal(); // Initial calculation
});



