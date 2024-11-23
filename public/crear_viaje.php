<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header('Location: auth_login.php'); // Redirige al login si no hay sesión activa
    exit;
}

require_once '../bd/connection.php'; // Asegúrate de que la ruta es correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_viaje = trim($_POST['nombre_viaje'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_final = $_POST['fecha_final'] ?? '';
    $presupuesto_base = $_POST['presupuesto_base'] ?? null; // Opcional
    $destino = trim($_POST['destino'] ?? null); // Opcional
    $estado = $_POST['estado'] ?? 'planificado'; // Estado por defecto
    $tipo_viaje = $_POST['tipo_viaje'] ?? 'solo'; // "solo" o "grupo"
    $id_usuario = $_SESSION['user_id'];

    if (empty($nombre_viaje) || empty($fecha_inicio) || empty($fecha_final)) {
        $_SESSION['error'] = "Nombre del viaje, fecha de inicio y fecha final son obligatorios.";
        header('Location: crear_viaje.php');
        exit;
    }

    try {
        $conn->beginTransaction(); // Inicia una transacción para garantizar consistencia

        // Insertar el viaje
        $sql = "INSERT INTO viajes (nombre_viaje, fecha_inicio, fecha_final, presupuesto_base, estado, destino, id_usuario, fecha_creacion) 
                VALUES (:nombre_viaje, :fecha_inicio, :fecha_final, :presupuesto_base, :estado, :destino, :id_usuario, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'nombre_viaje' => $nombre_viaje,
            'fecha_inicio' => $fecha_inicio,
            'fecha_final' => $fecha_final,
            'presupuesto_base' => $presupuesto_base,
            'estado' => $estado,
            'destino' => $destino,
            'id_usuario' => $id_usuario
        ]);

        $id_viaje = $conn->lastInsertId(); // Obtener el ID del viaje recién creado

        // Procesar viajes en grupo solo si el tipo es grupo
        if ($tipo_viaje === 'grupo') {
            // Lógica para grupos (como antes)
        }

        $conn->commit(); // Confirmar transacción
        $_SESSION['success'] = "Viaje creado exitosamente.";
        header('Location: dashboard.php');
        exit;
    } catch (Exception $e) {
        $conn->rollBack(); // Revertir cambios en caso de error
        $_SESSION['error'] = "Error al crear el viaje: " . $e->getMessage();
        header('Location: crear_viaje.php');
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Viaje - RutaLibre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="RutaLibre Logo">
        </div>
        <!-- Enlaces de navegación -->
        <div class="header-right">
            <a href="index.php" class="register-btn">Inicio</a>
            <a href="dashboard.php" class="register-btn">Viajes</a>
            <a href="asistencia_tecnica.php" class="register-btn">Asistencia técnica</a>
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

    <div class="form-background">
        <div class="form-container">
            <h2>Crear Nuevo Viaje</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <form action="crear_viaje.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="tipo_viaje" class="form-label">Tipo de Viaje:</label>
                    <select id="tipo_viaje" name="tipo_viaje" class="form-select" required>
                        <option value="solo">Solo</option>
                        <option value="grupo">En grupo</option>
                    </select>
                    <div class="invalid-feedback">Por favor, selecciona el tipo de viaje.</div>
                </div>

                <div id="grupoCampos" style="display: none;">
                    <div class="mb-3">
                        <label for="nombre_grupo" class="form-label">Nombre del Grupo</label>
                        <input type="text" class="form-control" id="nombre_grupo" name="nombre_grupo"
                            placeholder="Ej. Amigos de la universidad">
                    </div>

                    <div class="mb-3">
                        <label for="integrantes" class="form-label">Integrantes</label>
                        <input type="text" class="form-control" id="integrantes" name="integrantes"
                            placeholder="email1@example.com, email2@example.com">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nombre_viaje" class="form-label">Nombre del Viaje</label>
                    <input type="text" class="form-control" id="nombre_viaje" name="nombre_viaje" required>
                    <div class="invalid-feedback">Por favor, introduce un nombre para el viaje.</div>
                </div>

                <div class="mb-3">
                    <label for="destino" class="form-label">Destino</label>
                    <input type="text" class="form-control" id="destino" name="destino"
                        placeholder="Introduce el destino (opcional)">
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    <div class="invalid-feedback">Por favor, introduce la fecha de inicio.</div>
                </div>

                <div class="mb-3">
                    <label for="fecha_final" class="form-label">Fecha Final</label>
                    <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                    <div class="invalid-feedback">Por favor, introduce la fecha final.</div>
                </div>

                <div class="mb-3">
                    <label for="presupuesto_base" class="form-label">Presupuesto Base</label>
                    <input type="number" class="form-control" id="presupuesto_base" name="presupuesto_base"
                        placeholder="Introduce un presupuesto (opcional)">
                </div>

                <button type="submit" class="btn btn-primary w-100">Crear Viaje</button>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoViaje = document.getElementById('tipo_viaje');
            const grupoCampos = document.getElementById('grupoCampos');

            tipoViaje.addEventListener('change', function () {
                grupoCampos.style.display = this.value === 'grupo' ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>