document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    if (!container || !registerBtn || !loginBtn) return;

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
});

function googleLogin() {
    google.accounts.id.initialize({
        client_id: "TU_CLIENT_ID.apps.googleusercontent.com",
        callback: handleCredentialResponse
    });

    google.accounts.id.prompt();
}

function handleCredentialResponse(response) {
    console.log("Token de Google (JWT):", response.credential);
}
