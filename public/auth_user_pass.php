<?php
// Iniciar sesión
session_start();

// Incluir la conexión a la base de datos
include '../bd/connection.php';

// Variable para almacenar el mensaje
$message = "";

// Si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    try {
        // Verificar si el correo electrónico existe en la base de datos
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            // Generar un token único
            $token = bin2hex(random_bytes(50)); // Crear un token aleatorio

            // Insertar el token en la base de datos (para validarlo más tarde)
            $stmt = $conn->prepare("UPDATE usuarios SET password_reset_token = :token WHERE email = :email");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Enviar correo electrónico con el enlace para restablecer la contraseña
            $resetLink = "http://tu-dominio.com/auth_user_pass_reset.php?token=$token";
            $subject = "Restablece tu contraseña";
            $message = "Haz clic en el siguiente enlace para restablecer tu contraseña: $resetLink";
            $headers = "From: no-reply@tudominio.com";

            if (mail($email, $subject, $message, $headers)) {
                $message = "Te hemos enviado un correo con un enlace para restablecer tu contraseña.";
            } else {
                $message = "Hubo un error al intentar enviar el correo. Por favor, inténtalo nuevamente.";
            }
        } else {
            $message = "El correo electrónico no está registrado en nuestra base de datos.";
        }
    } catch (PDOException $e) {
        $message = "Error en la conexión: " . $e->getMessage();
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
    <link rel="icon" href="../images/favicon.ico">
    <title>Recupera tu Contraseña</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="login-page">
        <div class="auth-box">
            <h3>Recupera tu contraseña</h3>
            <p>Ingresa tu correo electrónico para restablecer la contraseña</p>

            <!-- Mostrar mensaje de éxito o error -->
            <?php if ($message): ?>
                <div class="alert">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario para recuperar la contraseña -->
            <form action="auth_user_pass.php" method="post">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                <button type="submit" class="btn">Restablecer</button>
            </form>

            <!-- Botón para volver a inicio -->
            <a href="index.html" class="btn btn-secondary">Volver a Inicio</a>
        </div>
    </div>
</body>

</html>
