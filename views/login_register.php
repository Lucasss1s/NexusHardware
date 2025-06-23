<?php
session_start();
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
            <h1>Crear Cuenta</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>o ingresa con tu email para el registro</span>
            <input type="text" name="full_name" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" name="phone" placeholder="Teléfono (opcional)">
            <button type="submit">Registrarse</button>
        </form>
    </div>

    <!-- LOGIN FORM -->
    <div class="form-container ingresar">
        <form action="/nexushardware/controllers/login.php" method="POST">
            <h1>Iniciar sesión</h1>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
            </div>
            <span>o ingresa con tu contraseña de cuenta</span>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <a href="#">¿Olvidaste tu contraseña?</a>
            <button type="submit">Ingresar</button>
        </form>
    </div>

    <!-- SLIDER TOGGLE -->
    <div class="toggle-container">
        <div class="toggle">
            <div class="toggle-panel toggle-left">
                <h1>Bienvenido!</h1>
                <p>Ingresa tus datos personales para usar nuestros servicios</p>
                <button class="hidden" id="login">Ingresar</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Hola, amigo!</h1>
                <p>Registra tus datos personales para usar nuestros servicios</p>
                <button class="hidden" id="register">Registrarse</button>
            </div>
        </div>
    </div>
</div>

<!-- TOGGLE SCRIPT -->
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

</body>
</html>
