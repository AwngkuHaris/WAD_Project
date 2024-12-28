document.addEventListener('DOMContentLoaded', () => {
    const specialtyItems = document.querySelectorAll('.specialty-item');

    specialtyItems.forEach((item) => {
        const info = item.querySelector('.specialty-info');
        let isOpen = false;

        item.addEventListener('click', () => {
            if (isOpen) {
                // Close animation
                anime({
                    targets: info,
                    height: 0,
                    opacity: [1, 0],
                    duration: 400,
                    easing: 'easeOutQuad',
                    complete: () => {
                        info.style.display = 'none'; // Hide the element
                    }
                });
            } else {
                // Open animation
                info.style.display = 'block'; // Make it visible
                info.style.height = '0px'; // Set initial height for animation
                anime({
                    targets: info,
                    height: info.scrollHeight + 'px',
                    opacity: [0, 1],
                    duration: 400,
                    easing: 'easeOutCubic'
                });
            }
            isOpen = !isOpen; // Toggle the state
        });
    });
});
