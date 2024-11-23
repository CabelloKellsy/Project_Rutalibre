<?php
session_start();
include '../bd/connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '../vendor/autoload.php'; // Carga Composer y Dotenv

// Cargar las variables del .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Por favor, ingresa un correo válido.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user) {
                $token = bin2hex(random_bytes(50));
                $stmt = $conn->prepare("
                    UPDATE usuarios 
                    SET password_reset_token = :token, token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) 
                    WHERE email = :email
                ");
                $stmt->bindParam(':token', $token);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                $resetLink = "http://localhost/Project_Rutalibre/public/auth_user_pass_reset.php?token=$token";

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = $_ENV['MAIL_HOST'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['MAIL_USERNAME'];
                    $mail->Password = $_ENV['MAIL_PASSWORD'];
                    $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
                    $mail->Port = $_ENV['MAIL_PORT'];

                    $mail->setFrom('apprutalibre@gmail.com', 'RutaLibre');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Restablece tu contraseña';
                    $mail->Body = "<p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                                   <a href='$resetLink'>$resetLink</a>";
                    $mail->AltBody = "Haz clic en el siguiente enlace para restablecer tu contraseña: $resetLink";

                    $mail->send();
                    $message = "Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.";
                } catch (Exception $e) {
                    error_log("Error al enviar el correo: " . $mail->ErrorInfo);
                    $message = "Hubo un error al enviar el correo. Inténtalo más tarde.";
                }
            } else {
                $message = "Si el correo está registrado, recibirás un enlace para restablecer tu contraseña.";
            }
        } catch (PDOException $e) {
            error_log("Error en la base de datos: " . $e->getMessage());
            $message = "Hubo un error. Inténtalo más tarde.";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña</title>
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
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Formulario para recuperar la contraseña -->
            <form action="" method="post">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                <button type="submit" class="btn">Restablecer</button>
            </form>

            <!-- Botón para volver a inicio -->
            <a href="index.php" class="btn btn-secondary">Volver a Inicio</a>
        </div>
    </div>
</body>
</html>
