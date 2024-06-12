if (document.querySelectorAll(".tab-link")) {
    document.addEventListener("DOMContentLoaded", function () {
        const tabLinks = document.querySelectorAll(".tab-link");
        tabLinks.forEach((tabLink) => {
            tabLink.addEventListener("click", function (event) {
                event.preventDefault();

                // Rimuovi la classe 'active' da tutti i tab
                tabLinks.forEach((link) => {
                    link.classList.remove("active");
                    link.removeAttribute("aria-current");
                });

                // Aggiungi la classe 'active' al tab corrente
                this.classList.add("active");
                this.setAttribute("aria-current", "page");

                const categorySlug = this.getAttribute("data-category-slug");

                // Effettua una richiesta AJAX per ottenere i prodotti della categoria selezionata
                fetch(`/category/${categorySlug}`)
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        return response.text();
                    })
                    .then((html) => {
                        document.querySelectorAll(".tab-content").innerHTML =
                            html;
                    })
                    .catch((error) =>
                        console.error(
                            "Errore nel caricamento dei prodotti:",
                            error
                        )
                    );
            });
        });
    });

    // document.addEventListener("DOMContentLoaded", function () {
    //     const tabs = document.querySelectorAll(".tab-link");
    //     const contents = document.querySelectorAll(".tab-pane");

    //     tabs.forEach((tab) => {
    //         tab.addEventListener("click", function (event) {
    //             event.preventDefault();

    //             // Rimuovi la classe 'active' e l'attributo 'aria-current' da tutte le tab
    //             tabs.forEach((t) => {
    //                 t.classList.remove("active");
    //                 t.setAttribute("aria-current", "false");
    //             });

    //             // Nascondi tutti i contenuti delle tab
    //             contents.forEach((content) => {
    //                 content.classList.add("hidden");
    //                 content.classList.remove("block");
    //             });

    //             // Aggiungi la classe 'active' e l'attributo 'aria-current' alla tab cliccata
    //             tab.classList.add("active");
    //             tab.setAttribute("aria-current", "page");

    //             // Mostra il contenuto della tab cliccata
    //             const index = tab.getAttribute("data-index");
    //             document
    //                 .getElementById("content-" + index)
    //                 .classList.remove("hidden");
    //             document
    //                 .getElementById("content-" + index)
    //                 .classList.add("block");
    //         });
    //     });
    // });
}
