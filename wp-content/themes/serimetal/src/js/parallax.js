import SimpleParallax from 'simple-parallax-js/vanilla';

document.addEventListener('DOMContentLoaded', () => {
    const images = document.querySelectorAll('img.parallax');

    if (!images.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    new SimpleParallax(images, {
        scale: 1.3,
        delay: 0.1,
        orientation: 'up',
        overflow: false
    });
});