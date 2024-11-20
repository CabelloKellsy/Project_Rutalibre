<?php
session_start(); // Iniciar la sesión para manejar las variables de sesión

include '../bd/connection.php'; // Incluir la conexión a la base de datos

$loginError = ""; // Variable para mensaje de error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Consulta para encontrar al usuario en la base de datos
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch();

        // Verificar si el usuario existe y si la contraseña es correcta
        if ($user && $password === $user['password']) {
            // Si el login es exitoso, guardar los datos del usuario en la sesión
            $_SESSION['username'] = $user['nombre']; // Almacenar el nombre en la sesión

            // Redirigir al dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $loginError = "Nombre de usuario o contraseña incorrectos.";
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
                <input type="email" name="username" class="form-control" placeholder="Correo electrónico" required>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                
                <!-- Mostrar mensaje de error si el login falla -->
                <?php if ($loginError): ?>
                    <div class="alert alert-danger">
                        <?php echo $loginError; ?>
                    </div>
                <?php endif; ?>
                
                <div class="remember-me-container">
                    <input type="checkbox" id="remember-me">
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
