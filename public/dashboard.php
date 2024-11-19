<?php
session_start(); // Iniciar la sesión para poder acceder a las variables de sesión

// Verificar si el usuario está logueado
if (!isset($_SESSION['username'])) {
    // Si no está logueado, redirigir al login
    header('Location: auth_login.php');
    exit;
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

    <title>Dashboard - RutaLibre</title>

    <!-- Estilo -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="dashboard-page">
        <div class="dashboard-box">
            <h2>Bienvenido al Dashboard</h2>
            <p>¡Estás conectado, <?php echo $_SESSION['username']; ?>!</p> <!-- Mostrar nombre de usuario conectado -->

            <p>CONECTADO</p> <!-- Mensaje de conexión -->

            <a href="logout.php" class="btn">Cerrar sesión</a> <!-- Botón de cierre de sesión -->
        </div>
    </div>
</body>

</html>

