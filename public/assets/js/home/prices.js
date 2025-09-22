document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.services-track');
    const slides = Array.from(document.querySelectorAll('.service-slide'));
    const prevButton = document.getElementById('prev-slide');
    const nextButton = document.getElementById('next-slide');

    if (!track || slides.length === 0) return;

    let currentIndex = 0;

    function getSlideWidth() {
        return slides[0].offsetWidth;
    }

    function getSlidesPerView() {
        return window.innerWidth > 992 ? 3 : window.innerWidth > 576 ? 2 : 1;
    }

    function getMaxIndex() {
        return Math.max(0, slides.length - getSlidesPerView());
    }

    function updateSliderPosition() {
        track.style.transform = `translateX(${-currentIndex * getSlideWidth()}px)`;

        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex >= getMaxIndex();

        prevButton.style.opacity = currentIndex === 0 ? '0.5' : '1';
        nextButton.style.opacity = currentIndex >= getMaxIndex() ? '0.5' : '1';
    }

    prevButton.addEventListener('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateSliderPosition();
        }
    });

    nextButton.addEventListener('click', function() {
        if (currentIndex < getMaxIndex()) {
            currentIndex++;
            updateSliderPosition();
        }
    });

    window.addEventListener('resize', function() {
        currentIndex = Math.min(currentIndex, getMaxIndex());
        updateSliderPosition();
    });

    updateSliderPosition();
});