document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const serviceId = button.getAttribute('data-service-id');

            try {
                const response = await fetch('/project_wad/backend/admin/add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ service_id: serviceId }),
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert('Service added to cart!');
                } else {
                    alert(`Error: ${result.message}`);
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                alert('Failed to add service to cart.');
            }
        });
    });
});
