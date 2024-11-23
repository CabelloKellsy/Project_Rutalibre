<?php
session_start(); // Iniciar la sesión

include '../bd/connection.php'; // Incluir conexión a la base de datos

$registerError = "";
$registerSuccess = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $registerError = "Las contraseñas no coinciden.";
    } else {
        try {
            // Verificar si el correo ya existe
            $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $registerError = "El correo ya está registrado.";
            } else {
                // Insertar nuevo usuario
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Encriptar la contraseña
                $stmt = $conn->prepare("
                    INSERT INTO usuarios (email, password, fecha_creacion) 
                    VALUES (:email, :password, NOW())
                ");
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->execute();

                $registerSuccess = "Usuario registrado con éxito. ¡Puedes iniciar sesión ahora!";
            }
        } catch (PDOException $e) {
            $registerError = "Error al registrar el usuario: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../assets/images/favicon.ico">
    <title>Regístrate</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="login-page">
        <div class="auth-box">
            <h2>Regístrate</h2>
            <form action="auth_register.php" method="post">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña" required>
                <?php if ($registerError): ?>
                    <div class="alert alert-danger"><?php echo $registerError; ?></div>
                <?php endif; ?>
                <?php if ($registerSuccess): ?>
                    <div class="alert alert-success"><?php echo $registerSuccess; ?></div>
                <?php endif; ?>
                <button type="submit" class="btn">Registrarse</button>
            </form>
            <p class="register-prompt">¿Ya tienes una cuenta? <a href="auth_login.php" class="register-link">Inicia sesión</a></p>
            <a href="index.php" class="btn btn-secondary">Volver a Inicio</a>
        </div>
    </div>
</body>

</html>
