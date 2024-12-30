document.addEventListener("DOMContentLoaded", () => {
    // Attach event listeners to quantity buttons
    document.querySelectorAll(".qty-btn").forEach(button => {
        button.addEventListener("click", () => {
            const serviceId = button.dataset.serviceId;
            const quantitySpan = document.getElementById(`quantity-${serviceId}`);
            const currentQuantity = parseInt(quantitySpan.textContent);
            const isIncrease = button.classList.contains("increase");
            
            // Determine the new quantity
            let newQuantity = isIncrease ? currentQuantity + 1 : currentQuantity - 1;
            if (newQuantity < 1) newQuantity = 1; // Minimum quantity is 1
            
            // Update the quantity in the UI
            quantitySpan.textContent = newQuantity;

            // Send an AJAX request to update the cart
            updateCartQuantity(serviceId, newQuantity);
        });
    });

    // Function to update the cart quantity in the database
    function updateCartQuantity(serviceId, newQuantity) {
        fetch("/project_wad/backend/update_cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ service_id: serviceId, quantity: newQuantity }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Cart updated successfully.");
                    // Optionally, update the total price dynamically
                    location.reload();
                } else {
                    alert("Failed to update cart.");
                }
            })
            .catch(error => console.error("Error:", error));
    }
});
