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

    scrolltop.addEventListener("click", () => {
        document.body.scrollTop = 0; // Safari
        document.documentElement.scrollTop = 0;
    });

    // const scrolltop = document.querySelector("#scrollTop");
    // const scrollFunction = () => {
    //     if (
    //         document.body.scrollTop > 100 ||
    //         document.documentElement.scrollTop > 100
    //     ) {
    //         scrolltop.classList.remove("opacity-0", "cursor-default");
    //         scrolltop.classList.add("opacity-100", "cursor-pointer");
    //     } else {
    //         scrolltop.classList.remove("opacity-100", "cursor-pointer");
    //         scrolltop.classList.add("opacity-0", "cursor-default");
    //     }
    // };
    // window.onscroll = () => {
    //     scrollFunction();
    // };
    // scrolltop.addEventListener("click", () => {
    //     document.body.scrollTop = 0; // Safari
    //     document.documentElement.scrollTop = 0;
    // });
}
