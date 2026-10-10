document.addEventListener('DOMContentLoaded', function() {
    // 1. Mobile nav toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.querySelector('.header');
        if (header) {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });

    // 2. Court availability grid logic (Mock logic for UI interaction)
    const courtGrid = document.querySelector('.court-grid');
    if (courtGrid) {
        const slots = document.querySelectorAll('.grid-cell.slot:not(.booked)');
        slots.forEach(slot => {
            slot.addEventListener('click', function() {
                this.classList.toggle('selected');
                updatePriceCalculation();
            });
        });
    }

    function updatePriceCalculation() {
        const selectedSlots = document.querySelectorAll('.grid-cell.slot.selected').length;
        const priceElement = document.getElementById('total-price');
        if (priceElement) {
            // Mock calculation: 100k per slot
            priceElement.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(selectedSlots * 100000);
        }
    }

    // 4. Flash message auto-dismiss
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    });

    // 8. Counter animations
    const counters = document.querySelectorAll('.counter-animate');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 2000;
        const increment = target / (duration / 16); // 60fps
        
        let current = 0;
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.innerText = Math.ceil(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.innerText = target;
            }
        };
        updateCounter();
    });
});
