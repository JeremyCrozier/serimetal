// Smooth scroll for anchor links
document.addEventListener('DOMContentLoaded', () => {
  // Select all anchor links
  const anchorLinks = document.querySelectorAll('a[href^="#"]');
  
  // Add click event listener to each anchor link
  anchorLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // Prevent default anchor click behavior
      e.preventDefault();
      
      // Get the target element from the href attribute
      const targetId = this.getAttribute('href');
      
      // Skip if it's just "#" (to avoid scrolling to top)
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      
      // If target element exists, scroll to it smoothly
      if (targetElement) {
        // Get header height for offset (if header exists)
        const header = document.querySelector('#header');
        const headerOffset = header ? header.offsetHeight : 0;
        
        // Calculate position to scroll to (with header offset)
        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerOffset;
        
        // Perform smooth scroll
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });
});
