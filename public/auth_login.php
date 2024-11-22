<?php
session_start();
ob_start();

include '../bd/connection.php'; // Conexión a la base de datos

$loginError = "";

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']); // Verificar si se marcó el checkbox

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // Verificar contraseña
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id_usuario']; // Guardar el ID del usuario en la sesión

            // Si se marcó "Recuérdame", configurar cookies
            if ($remember_me) {
                setcookie('remember_email', $email, time() + (30 * 24 * 60 * 60), "/"); // 30 días de duración
                setcookie('remember_password', $password, time() + (30 * 24 * 60 * 60), "/"); // 30 días de duración
            } else {
                // Si no se marcó, eliminar cookies
                setcookie('remember_email', '', time() - 3600, "/");
                setcookie('remember_password', '', time() - 3600, "/");
            }

            // Redirigir a dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $loginError = "Correo o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        $loginError = "Error en la conexión: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../assets/images/favicon.ico">

    <title>Iniciar sesión en RutaLibre</title>

    <!-- Estilo -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="login-page">
        <div class="auth-box">
            <h2>Inicia sesión</h2>
            <p>Comienza con nosotros</p>
            <!-- Formulario de login -->
            <form action="auth_login.php" method="post">
                <!-- Campo de correo con cookie precargada si existe -->
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="Correo electrónico" 
                    value="<?= isset($_COOKIE['remember_email']) ? htmlspecialchars($_COOKIE['remember_email']) : '' ?>" 
                    required
                >
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    placeholder="Contraseña" 
                    value="<?= isset($_COOKIE['remember_password']) ? htmlspecialchars($_COOKIE['remember_password']) : '' ?>" 
                    required
                >

                <!-- Mostrar mensaje de error si el login falla -->
                <?php if ($loginError): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($loginError); ?>
                    </div>
                <?php endif; ?>

                <!-- Checkbox de "Recuérdame" -->
                <div class="remember-me-container">
                    <input 
                        type="checkbox" 
                        id="remember-me" 
                        name="remember_me" 
                        <?= isset($_COOKIE['remember_email']) ? 'checked' : '' ?>
                    >
                    <label for="remember-me">Recuérdame</label>
                </div>
                <button type="submit" class="btn">Iniciar sesión</button>
            </form>
            <a href="auth_user_pass.php" class="forgot-password">¿Olvidaste tu contraseña?</a>
            <p class="register-prompt">¿No tienes una cuenta? <a href="auth_register.php" class="register-link">Regístrate</a></p>

            <!-- Botón para volver a inicio -->
            <a href="index.html" class="btn btn-secondary">Volver a Inicio</a>
        </div>
    </div>
</body>

</html>

