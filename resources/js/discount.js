document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector("#apply-discount-btn")) {
        const applyDiscountBtn = document.getElementById("apply-discount-btn");
        const discountCodeInput = document.getElementById(
            "discount-code-input"
        );
        const discountMessage = document.getElementById("discount-message");
        const discountAmountElement =
            document.getElementById("discount-amount");
        const discountValue = document.getElementById("discountValue");
        const cartTotal = document.getElementById("cartTotal");
        const cartSubtotal = document.getElementById("cartSubtotal");
        const shippingCost = document.getElementById("shippingCost");
        const discountCodeHidden = document.getElementById(
            "discount-code-hidden"
        );

        applyDiscountBtn.addEventListener("click", function () {
            const code = discountCodeInput.value;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/apply-discount", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ code: code }),
            })
                .then((response) => {
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        discountMessage.textContent =
                            "Codice sconto applicato con successo!";
                        discountMessage.className = "text-sm text-green-600";
                        discountAmountElement.style.display = "flex";

                        const subtotal = parseFloat(
                            cartSubtotal.textContent.replace("€", "")
                        );
                        const shipping = parseFloat(
                            shippingCost.textContent.replace("€", "")
                        );
                        const discountAmount =
                            (subtotal * data.percentage) / 100;
                        const newTotal = subtotal - discountAmount + shipping;

                        discountValue.textContent = `-€${discountAmount.toFixed(
                            2
                        )}`;
                        cartTotal.textContent = `€${newTotal.toFixed(2)}`;
                        discountCodeHidden.value = code;
                    } else {
                        discountMessage.textContent =
                            data.message || "Codice sconto non valido";
                        discountMessage.className = "text-sm text-red-600";
                        discountAmountElement.style.display = "none";
                        discountCodeHidden.value = "";
                    }
                })
                .catch((error) => {
                    console.error(
                        "Errore nell'applicazione del codice sconto:",
                        error
                    );
                    discountMessage.textContent =
                        "Si è verificato un errore. Riprova più tardi.";
                    discountMessage.className = "text-sm text-red-600";
                });
        });
    }
});
