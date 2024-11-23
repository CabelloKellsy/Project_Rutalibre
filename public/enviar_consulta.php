<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $mensaje = $_POST['mensaje'];

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST']; // Cargado desde .env
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME']; // Cargado desde .env
        $mail->Password = $_ENV['MAIL_PASSWORD']; // Cargado desde .env
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION']; // Cargado desde .env
        $mail->Port = $_ENV['MAIL_PORT']; // Cargado desde .env

        // Destinatario
        $mail->setFrom($email, $nombre);
        $mail->addReplyTo($email, $nombre);
        $mail->addAddress('apprutalibre@gmail.com', 'Soporte RutaLibre');

        // Contenido
        $mail->isHTML(true);
        $mail->Subject = 'Consulta de Soporte Técnico';
        $mail->Body = "<strong>Nombre:</strong> $nombre<br><strong>Email:</strong> $email<br><strong>Mensaje:</strong><br>$mensaje";

        $mail->send();
        echo "Mensaje enviado con éxito";
    } catch (Exception $e) {
        echo "Error al enviar el mensaje: {$mail->ErrorInfo}";
    }
}
?>
