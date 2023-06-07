const menuBtn = document.getElementById("menu-btn");
const menuContent = document.getElementById("menu-content");

menuBtn.addEventListener("click", function() {
    menuBtn.classList.toggle("active");

    menuContent.classList.toggle("active");
});

var accordionButtons = document.querySelectorAll(".accordion");

accordionButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        this.classList.toggle("active");

        var panel = this.nextElementSibling;

        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
});

// Step 1: Save accordion state on click
let accordions = document.querySelectorAll('.accordion');
accordions.forEach((accordion, index) => {
    accordion.addEventListener('click', () => {
        localStorage.setItem('accordion' + index, accordion.nextElementSibling.style.display === 'block' ? 'open' : 'closed');
    });
});

// Step 2: Load accordion state on page load
window.addEventListener('DOMContentLoaded', (event) => {
    accordions.forEach((accordion, index) => {
        let state = localStorage.getItem('accordion' + index);
        if (state === 'open') {
            accordion.click();
        }
    });
});

// Save accordion state on click
accordionButtons.forEach((accordion, index) => {
    accordion.addEventListener('click', () => {
        localStorage.setItem('accordion' + index, accordion.nextElementSibling.style.display === 'block' ? 'open' : 'closed');
    });
});

// Load accordion state on page load
window.addEventListener('DOMContentLoaded', (event) => {
    accordionButtons.forEach((accordion, index) => {
        let state = localStorage.getItem('accordion' + index);
        if (state === 'open') {
            accordion.click();
        }
    });
});
