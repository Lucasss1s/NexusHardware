
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("payment-form");
    if (!form) return;
    
    form.addEventListener("submit", function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add("was-validated");
    });
});
