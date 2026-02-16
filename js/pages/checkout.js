document.addEventListener('DOMContentLoaded', () => {

    // Form validation
    const form = document.getElementById('addressForm');
    if (form) {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    }

    // Autocomplete
    const autoBtn = document.getElementById('autoCompleteBtn');
    if (!autoBtn || !window.addressData) return;

    autoBtn.addEventListener('click', (e) => {
        e.preventDefault();

        const address = window.addressData;
        document.getElementById('street').value = address.street;
        document.getElementById('number').value = address.number;
        document.getElementById('city').value = address.city;
        document.getElementById('state').value = address.state;
        document.getElementById('postal_code').value = address.postalCode;
        document.getElementById('country').value = address.country;
        document.getElementById('description').value = address.description ?? '';
    });
});
