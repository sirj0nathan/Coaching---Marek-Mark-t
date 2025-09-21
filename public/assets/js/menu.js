document.addEventListener('DOMContentLoaded', function() {
    const statsSection = document.querySelector('.stats-section');

    if (!statsSection) {
        return;
    }

    const animateCount = (element) => {
        const target = parseInt(element.getAttribute('data-count'), 10);
        const duration = 2000; // 2 seconds
        const stepTime = 20; // update every 20ms
        const totalSteps = duration / stepTime;
        const increment = target / totalSteps;
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                clearInterval(timer);
                element.textContent = target + '+';
            } else {
                element.textContent = Math.ceil(current) + '+';
            }
        }, stepTime);
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(animateCount);
                observer.unobserve(entry.target); // Animate only once
            }
        });
    }, {
        threshold: 0.5 // Trigger when 50% of the element is visible
    });

    observer.observe(statsSection);
});