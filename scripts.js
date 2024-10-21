// scripts.js

document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('background-video');
    let pausedvid = false; // Use let instead of const for variable that will change

    // Function to handle video playback based on scroll position
    function handleScroll() {
        const rect = video.getBoundingClientRect();
        const isVideoInView = rect.top < window.innerHeight && rect.bottom >= 0;

        if (isVideoInView) {
            if (pausedvid) {
                video.play();
                pausedvid = false; // Correct assignment
            }
        } else {
            if (!pausedvid) {
                video.pause();
                pausedvid = true; // Correct assignment
            }
        }
    }

    // Handle click events to toggle video play/pause
    video.addEventListener('click', () => {
        if (pausedvid) {
            video.play();
            pausedvid = false; // Correct assignment
        } else {
            video.pause();
            pausedvid = true; // Correct assignment
        }
    });

    // Ensure the video starts playing on page load
    video.play().catch(error => {
        console.error('Video playback error:', error);
    });

    // Handle scroll events
    window.addEventListener('scroll', handleScroll);

    // Initial check to set video state based on its visibility
    handleScroll();
});
document.addEventListener('DOMContentLoaded', function () {
    const elements = document.querySelectorAll('.fade-in');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    });

    elements.forEach(element => {
        observer.observe(element);
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.fashion-container .slide-text');
    const imageElement = document.querySelector('.fashion-container img');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const index = [...items].indexOf(entry.target);
                
                // Show items sequentially and update the image
                setTimeout(() => {
                    entry.target.classList.add('visible');
                    
                    // Change the image source based on the visible text
                    if (entry.target.hasAttribute('data-image')) {
                        imageElement.src = entry.target.getAttribute('data-image');
                    }
                }, index * 200); // Adjust the delay time (in milliseconds) for each item
                
                // Stop observing once it becomes visible
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    items.forEach(item => {
        observer.observe(item);
    });
});




