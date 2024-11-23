<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header('Location: auth_login.php'); // Redirige al login si no hay sesión activa
    exit;
}

include '../bd/connection.php'; // Incluir la conexión a la base de datos

$userId = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión
$message = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $apellidos = htmlspecialchars(trim($_POST['apellido']));
    $tipo_cuenta = htmlspecialchars($_POST['tipo_usuario']);
    $contraseña_actual = $_POST['password'];
    $nueva_contraseña = $_POST['nueva_contraseña'];
    $confirmar_contraseña = $_POST['confirmar_contraseña'];

    try {
        // Actualizar perfil básico (nombre, apellidos y tipo de cuenta)
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, tipo_usuario = :tipo_usuario WHERE id_usuario = :id");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellidos);
        $stmt->bindParam(':tipo_usuario', $tipo_cuenta);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        // Actualizar contraseña si se proporciona
        if (!empty($contraseña_actual) && !empty($nueva_contraseña) && !empty($confirmar_contraseña)) {
            // Obtener la contraseña actual de la base de datos
            $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id_usuario = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($contraseña_actual, $user['password'])) {
                if ($nueva_contraseña === $confirmar_contraseña) {
                    $hashedPassword = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE usuarios SET password = :password WHERE id_usuario = :id");
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':id', $userId);
                    $stmt->execute();
                    $message = "Perfil y contraseña actualizados exitosamente.";
                } else {
                    $message = "Las nuevas contraseñas no coinciden.";
                }
            } else {
                $message = "La contraseña actual no es correcta.";
            }
        } else {
            $message = "Perfil actualizado exitosamente.";
        }
    } catch (PDOException $e) {
        $message = "Error al actualizar el perfil: " . $e->getMessage();
    }
}

// Obtener datos actuales del usuario
try {
    $stmt = $conn->prepare("SELECT nombre, apellido, tipo_usuario FROM usuarios WHERE id_usuario = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al cargar los datos del usuario: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Configuración de Perfil</h2>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if ($message): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="configuracion_perf.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="apellido" class="form-label">Apellidos</label>
                <input type="text" name="apellido" id="apellido" class="form-control" value="<?php echo htmlspecialchars($user['apellido']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="tipo_usuario" class="form-label">Tipo de cuenta</label>
                <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                    <option value="Particular" <?php echo $user['tipo_usuario'] === 'Particular' ? 'selected' : ''; ?>>Particular</option>
                    <option value="Empresa" <?php echo $user['tipo_usuario'] === 'Empresa' ? 'selected' : ''; ?>>Empresa</option>
                </select>
            </div>

            <hr>

            <h5>Cambiar Contraseña</h5>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña Actual</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>

            <div class="mb-3">
                <label for="nueva_contraseña" class="form-label">Nueva Contraseña</label>
                <input type="password" name="nueva_contraseña" id="nueva_contraseña" class="form-control">
            </div>

            <div class="mb-3">
                <label for="confirmar_contraseña" class="form-label">Confirmar Nueva Contraseña</label>
                <input type="password" name="confirmar_contraseña" id="confirmar_contraseña" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="dashboard.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
