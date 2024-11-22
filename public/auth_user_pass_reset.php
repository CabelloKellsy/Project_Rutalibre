<?php
session_start();
include '../bd/connection.php'; // Conexión a la base de datos

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ruta donde Composer instaló PHPMailer

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Verificar si el token es válido y no ha expirado
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE password_reset_token = :token AND token_expiry > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            $message = "El enlace para restablecer la contraseña no es válido o ha expirado.";
            $invalidToken = true;
        }
    } catch (PDOException $e) {
        $message = "Error en la conexión: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $message = "Las contraseñas no coinciden.";
    } else {
        try {
            // Verificar si el token es válido y no ha expirado
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE password_reset_token = :token AND token_expiry > NOW()");
            $stmt->bindParam(':token', $token);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user) {
                // Actualizar la contraseña en la base de datos
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET password = :password, password_reset_token = NULL, token_expiry = NULL WHERE id_usuario = :id");
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':id', $user['id_usuario']);
                $stmt->execute();

                $message = "Tu contraseña ha sido actualizada exitosamente.";
                $success = true;
            } else {
                $message = "El enlace para restablecer la contraseña no es válido o ha expirado.";
            }
        } catch (PDOException $e) {
            $message = "Error en la conexión: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-page">
        <div class="auth-box">
            <h3>Restablecer Contraseña</h3>

            <?php if ($message): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($invalidToken) && !isset($success)): ?>
                <!-- Formulario para establecer nueva contraseña -->
                <form action="auth_user_pass_reset.php" method="post">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="password" name="new_password" class="form-control" placeholder="Nueva contraseña" required>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
                    <button type="submit" class="btn">Restablecer Contraseña</button>
                </form>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <a href="auth_login.php" class="btn btn-primary">Iniciar sesión</a>
            <?php else: ?>
                <a href="index.html" class="btn btn-secondary">Volver a Inicio</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
