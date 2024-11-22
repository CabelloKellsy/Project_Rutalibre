<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header('Location: auth_login.php'); // Redirige al login si no hay sesión activa
    exit;
}
// Obtener el user_id de la sesión
$userId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RutaLibre</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Estilos personalizados opcionales -->
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="container">
                <!-- Logotipo -->
                <a class="logo" href="#">
                    <img src="../assets/images/logo.png" alt="RutaLibre Logo">
                </a>

                <!-- Enlaces de navegación -->
                <div class="nav-links">
                    <a href="#" class="nav-link">Viajes</a>
                    <a href="#" class="nav-link">Asistencia técnica</a>
                </div>

                <!-- Menú de usuario -->
                <div class="user-menu">
                    <a href="#" class="user-icon">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown">
                        <a href="#" class="dropdown-item">Configuración de perfil</a>
                        <a href="logout.php" class="dropdown-item">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>


    <!-- Contenido principal -->
    <div class="container mt-5">
        <h2 class="mb-4">Tus viajes</h2>

        <!-- Botón para añadir un viaje -->
        <div class="mt-4">
            <a href="crear_viaje.php" class="btn btn-primary">+ Añadir un viaje</a>
        </div><br>

        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="proximos-viajes-tab" data-bs-toggle="tab" data-bs-target="#proximos-viajes" type="button" role="tab" aria-controls="proximos-viajes" aria-selected="true">Próximos viajes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="viajes-anteriores-tab" data-bs-toggle="tab" data-bs-target="#viajes-anteriores" type="button" role="tab" aria-controls="viajes-anteriores" aria-selected="false">Viajes anteriores</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="proximos-viajes" role="tabpanel" aria-labelledby="proximos-viajes-tab">
                <div class="table-responsive mt-3">
                    <table class="table table-striped" id="tablaProximosViajes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Final</th>
                                <th>Presupuesto</th>
                                <th>Estado</th>
                                <th>Id del Usuario</th>
                                <th>Fecha de creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaProximosViajesBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="viajes-anteriores" role="tabpanel" aria-labelledby="viajes-anteriores-tab">
                <!-- Contenido para viajes anteriores -->
            </div>
        </div>

        <script>
            cargarProximosViajes(<?php echo $userId; ?>)
        </script>

    </div>
    <script src="../js/viajes_usuario.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>