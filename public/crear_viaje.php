<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header('Location: auth_login.php'); // Redirige al login si no hay sesión activa
    exit;
}

require_once '../bd/connection.php'; // Asegúrate de que la ruta es correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $nombre_viaje = trim($_POST['nombre_viaje'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_final = $_POST['fecha_final'] ?? '';
    $presupuesto_base = $_POST['presupuesto_base'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $id_usuario = $_SESSION['user_id'];

    // Validar los datos
    if (empty($nombre_viaje) || empty($fecha_inicio) || empty($fecha_final) || empty($presupuesto_base) || empty($estado)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
    } else {
        try {
            // Preparar y ejecutar la consulta
            $sql = "INSERT INTO viajes (nombre_viaje, fecha_inicio, fecha_final, presupuesto_base, estado, id_usuario, fecha_creacion) 
                    VALUES (:nombre_viaje, :fecha_inicio, :fecha_final, :presupuesto_base, :estado, :id_usuario, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'nombre_viaje' => $nombre_viaje,
                'fecha_inicio' => $fecha_inicio,
                'fecha_final' => $fecha_final,
                'presupuesto_base' => $presupuesto_base,
                'estado' => $estado,
                'id_usuario' => $id_usuario
            ]);

            // Almacenar el mensaje de éxito en la sesión
            $_SESSION['success'] = "Viaje creado exitosamente.";
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error al crear el viaje: " . $e->getMessage();
        }
    }

    // Redirigir al dashboard en caso de error
    header('Location: dashboard.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Viaje - RutaLibre</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Asegúrate de que la ruta es correcta -->
</head>

<body>
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="RutaLibre Logo">
        </div>
        <!-- Enlaces de navegación -->
        <div class="nav-links">
            <a href="dashboard.php" class="nav-link">Viajes</a>
            <a href="#" class="nav-link">Asistencia técnica</a>
        </div>
        <!-- Menú de usuario -->
        <div class="user-menu">
            <a href="#" class="user-icon">
                <i class="fas fa-user-circle"></i>
            </a>
            <div class="dropdown">
                <a href="configuracion_perf.php" class="dropdown-item">Configuración de perfil</a>
                <a href="logout.php" class="dropdown-item">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="container mt-5">
        <div class="crear-viaje-form">
            <h2>Crear Nuevo Viaje</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="crear_viaje.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="nombre_viaje" class="form-label">Nombre del Viaje</label>
                    <input type="text" class="form-control" id="nombre_viaje" name="nombre_viaje" required
                        placeholder="Ej. Viaje a la montaña">
                    <div class="invalid-feedback">
                        Por favor, introduce el nombre del viaje.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    <div class="invalid-feedback">
                        Por favor, introduce la fecha de inicio.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final</label>
                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                    <div class="invalid-feedback">
                        Por favor, introduce la fecha final.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="presupuesto_base" class="form-label">Presupuesto Base</label>
                    <input type="number" class="form-control" id="presupuesto_base" name="presupuesto_base" required
                        min="0" placeholder="Ej. 500">
                    <div class="invalid-feedback">
                        Por favor, introduce el presupuesto base.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="" disabled selected>Selecciona el estado</option>
                        <option value="Planificado">Planificado</option>
                        <option value="En Curso">En Curso</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                    <div class="invalid-feedback">
                        Por favor, selecciona el estado.
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Crear Viaje</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS y validación personalizada -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript para la validación de formularios de Bootstrap
        (function () {
            'use strict'

            // Obtener todos los formularios que necesiten validación
            var forms = document.querySelectorAll('.needs-validation')

            // Bucle sobre ellos y prevenir el envío
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>