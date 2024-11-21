<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['email'])) {
    header('Location: auth_login.php'); // Redirige al login si no hay sesión activa
    exit;
}
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

        <!-- Pestañas -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="proximos-viajes-tab" data-bs-toggle="tab" data-bs-target="#proximos-viajes" type="button" role="tab" aria-controls="proximos-viajes" aria-selected="true">Próximos viajes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="viajes-anteriores-tab" data-bs-toggle="tab" data-bs-target="#viajes-anteriores" type="button" role="tab" aria-controls="viajes-anteriores" aria-selected="false">Viajes anteriores</button>
            </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content" id="myTabContent">
            <!-- Próximos viajes -->
            <div class="tab-pane fade show active" id="proximos-viajes" role="tabpanel" aria-labelledby="proximos-viajes-tab">
                <div class="mt-4">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x200" class="img-fluid rounded-start" alt="Imagen del viaje">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Viaje a Malta</h5>
                                    <p class="card-text"><strong>Destino:</strong> Valletta, Malta</p>
                                    <p class="card-text"><strong>Fecha:</strong> Nov 21, 2024 (1 día)</p>
                                    <div class="d-flex gap-3">
                                        <a href="#" class="btn btn-outline-secondary">Gestionar uso compartido</a>
                                        <a href="editar_viaje.php" class="btn btn-outline-primary">Editar información de viaje</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Viajes anteriores -->
            <div class="tab-pane fade" id="viajes-anteriores" role="tabpanel" aria-labelledby="viajes-anteriores-tab">
                <div class="mt-4">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x200" class="img-fluid rounded-start" alt="Imagen del viaje">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">Viaje a Madrid</h5>
                                    <p class="card-text"><strong>Destino:</strong> Madrid, España</p>
                                    <p class="card-text"><strong>Fecha:</strong> Oct 10, 2023 (3 días)</p>
                                    <div class="d-flex gap-3">
                                        <a href="#" class="btn btn-outline-secondary">Ver detalles</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón para añadir un viaje -->
        <div class="mt-4">
            <a href="crear_viaje.php" class="btn btn-primary">+ Añadir un viaje</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>