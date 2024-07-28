// * Scroll to top
if (document.querySelector("#scrollTop")) {
    const scrolltop = document.querySelector("#scrollTop");

    const scrollFunction = () => {
        const docElement = document.documentElement;
        const docBody = document.body;
        const scrollTop = docElement.scrollTop || docBody.scrollTop;
        const scrollHeight = docElement.scrollHeight || docBody.scrollHeight;
        const clientHeight = docElement.clientHeight;

        // Calculate the percentage of page scrolled
        const scrollPercentage =
            (scrollTop / (scrollHeight - clientHeight)) * 100;

        if (scrollPercentage > 1) {
            scrolltop.classList.remove("opacity-0", "cursor-default");
            scrolltop.classList.add("opacity-100", "cursor-pointer");
        } else {
            scrolltop.classList.remove("opacity-100", "cursor-pointer");
            scrolltop.classList.add("opacity-0", "cursor-default");
        }

        // Set the clip-path of the after pseudo-element based on the scroll percentage
        scrolltop.style.setProperty(
            "--clip-path",
            `inset(0% ${100 - scrollPercentage}% 0% 0%)`
        );
    };

    window.onscroll = () => {
        scrollFunction();
    };

    const easeOutQuad = (t) => t * (2 - t);

    const scrollToTop = () => {
        const start =
            document.documentElement.scrollTop || document.body.scrollTop;
        const duration = 500;
        const startTime = performance.now();

        const animateScroll = (currentTime) => {
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1); // Ensure the progress does not exceed 1
            const ease = easeOutQuad(progress);
            const currentScroll = start * (1 - ease);

            document.body.scrollTop = currentScroll; // For Safari
            document.documentElement.scrollTop = currentScroll;

            if (progress < 1) {
                requestAnimationFrame(animateScroll);
            }
        };

        requestAnimationFrame(animateScroll);
    };

    scrolltop.addEventListener("click", scrollToTop);
}
