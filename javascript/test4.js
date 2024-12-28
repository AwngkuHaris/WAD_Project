document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.button'); // Select all elements with class 'button'
    const wrappers = document.querySelectorAll('.wrapper'); // Select all elements with class 'wrapper'

    buttons.forEach((button, index) => {
        let isOpen = false; // Track open/close state for each button
        const wrapper = wrappers[index]; // Match button with corresponding wrapper

        button.addEventListener('click', () => {
            if (isOpen) {
                anime({
                    targets: wrapper,
                    height: 0,
                    opacity: [1, 0],
                    duration: 400,
                    easing: 'easeOutQuad',
                    complete() {
                        wrapper.style.display = 'none';
                        isOpen = false;
                    }
                });
            } else {
                wrapper.style.display = 'block'; // Make the wrapper visible
                wrapper.style.height = '0px'; // Start animation from 0 height
                anime({
                    targets: wrapper,
                    height: wrapper.scrollHeight + 'px',
                    opacity: [0, 1],
                    duration: 400,
                    easing: 'easeOutCubic'
                });
                isOpen = true;
            }
        });
    });
});
