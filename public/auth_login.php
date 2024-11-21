<?php
session_start(); // Iniciar la sesión
ob_start(); // Activar el buffer de salida

include '../bd/connection.php'; // Incluir conexión a la base de datos

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        // if ($user && password_verify($password, $user['password'])) {
        //     session_regenerate_id(true);
        //     $_SESSION['email'] = $user['email'];
        //     header('Location: dashboard.php');
        //     exit;
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['id_usuario']; // Agregar el ID del usuario a la sesión
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
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
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