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

// Wrap every letter in a span
var textWrapper = document.querySelector('.ml2');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: false}) // Set loop to false
  .add({
    targets: '.ml2 .letter',
    scale: [4, 1],
    opacity: [0, 1],
    translateZ: 0,
    easing: "easeOutExpo",
    duration: 950,
    delay: (el, i) => 70 * i
  }).add({
    targets: '.ml2',
    opacity: 1, // Keep the opacity at 1
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });

  var textWrapper = document.querySelector('.ml3');
textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

anime.timeline({loop: false}) // Set loop to false
  .add({
    targets: '.ml3 .letter',
    scale: [4, 1],
    opacity: [0, 1],
    translateZ: 0,
    easing: "easeOutExpo",
    duration: 950,
    delay: (el, i) => 70 * i
  }).add({
    targets: '.ml3',
    opacity: 1, // Keep the opacity at 1
    duration: 1000,
    easing: "easeOutExpo",
    delay: 1000
  });