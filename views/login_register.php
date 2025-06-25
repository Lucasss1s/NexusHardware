<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Register</title>
    <link rel="stylesheet" href="/nexushardware/css/login-register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="success-message"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="container" id="container">
    <!-- REGISTRATION FORM -->
    <div class="form-container registrarse">
        <form action="/nexushardware/controllers/register.php" method="POST">
            <h1>Create Account</h1>
            <div class="social-icons">
                <a href="#" class="icon" onclick="googleLogin(); return false;">
                    <i class="fa-brands fa-google-plus-g"></i>
                </a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>or sign up with your email</span>
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone (optional)">
            <button type="submit">Sign Up</button>
        </form>
    </div>

    <!-- LOGIN FORM -->
    <div class="form-container ingresar">
        <form action="/nexushardware/controllers/login.php" method="POST">
            <h1>Sign In</h1>
            <div class="social-icons">
                <a href="#" class="icon" onclick="googleLogin(); return false;">
                    <i class="fa-brands fa-google-plus-g"></i>
                </a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>or use your account credentials</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="#">Forgot your password?</a>
            <button type="submit">Sign In</button>
        </form>
    </div>

    <!-- SLIDER TOGGLE -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Welcome!</h1>
                <p>Enter your personal information to use our services</p>
                <button class="hidden" id="login">Sign In</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hello, friend!</h1>
                <p>Register your personal information to use our services</p>
                <button class="hidden" id="register">Sign Up</button>
            </div>
        </div>
    </div>
</div>

</body>

<script>
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });
    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });
</script>


<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
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
</script>
</html>
