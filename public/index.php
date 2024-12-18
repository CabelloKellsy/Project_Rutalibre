<?php
session_start(); // Iniciar la sesión para verificar si el usuario está logueado
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App RutaLibre - Inicio</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="description"
        content="Con RutaLibre organiza tus viajes en grupo de manera fácil. Planifica actividades, gestiona gastos compartidos y elige el mejor destino. Todo en una sola plataforma.">
    <meta name="keywords"
        content="viajes en grupo, planificar viajes, actividades en grupo, control de gastos, destinos turísticos, organización de viajes, plataforma de viajes">
    <meta name="author" content="RutaLibre">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="icon" href="../images/favicon.ico">
</head>

<!-- public\css\home.html -->

<body>
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="RutaLibre Logo">
        </div>
        <div class="header-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Si el usuario está logueado -->
                <a href="dashboard.php" class="register-btn">Ir al Dashboard</a>
                <div class="user-menu">
                    <a href="#" class="user-icon">
                        <i class="fas fa-user-circle"></i>
                    </a>
                    <div class="dropdown">
                        <a href="configuracion_perf.php" class="dropdown-item">Configuración de perfil</a>
                        <a href="logout.php" class="dropdown-item">Cerrar sesión</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Si el usuario NO está logueado -->
                <a href="auth_register.php">
                    <button class="register-btn">Registrarse</button>
                </a>
                <a href="auth_login.php" class="user-icon">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php endif; ?>
        </div>
    </header>


    <button id="back-to-top" title="Volver al inicio">𖤂</button>

    <section class="hero">
        <div class="carousel">
            <div class="carousel-images">
                <img src="../assets/images/hero1.webp" alt="grupo de amigos sentados en un banco viaje">
                <img src="../assets/images/hero2.webp" alt="grupo de amigas playa viaje">
                <img src="../assets/images/hero3.webp" alt="grupo de amigos viaje cultural">
            </div>
        </div>

        <div class="hero-content">
            <h1>RuTaLibre</h1>
            <h2>Planifica y gestiona tus viajes fácilmente</h2>
            <p>Con RutaLibre, organiza tus viajes en grupo de manera fácil. Elige el destino mediante votaciones, crea
                cronogramas, gestiona gastos compartidos y descubre nuevas actividades y alojamientos según tu
                ubicación. </p>
            <p>Todo en una sola plataforma.</p>
            <button class="cta-button">¡Empieza ahora!</button>
        </div>
        </div>
    </section>

    <section id="funcionalidades" class="features-section">
        <h2 class="titulo-oscuro">Funcionalidades de RutaLibre</h2>
        <img src="../assets/images/decoracion.png" alt="Decoración de ruta" class="title-decoration">
        <div class="features">
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-map-marked-alt icon"></i></span>
                <h3>Votación de destinos</h3>
                <p>Permite a los usuarios votar por sus destinos favoritos.</p>
                <a href="#votacion-destinos" class="more-info-btn">»</a>
            </div>
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-calendar-check icon"></i></span>
                <h3>Gestiona actividades</h3>
                <p>Organiza las actividades diarias de tu viaje.</p>
                <a href="#gestiona-actividades" class="more-info-btn">»</a>
            </div>
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-lightbulb icon"></i></span>
                <h3>Sugerencias de planes</h3>
                <p>Recibe ideas de actividades y planes para cada destino.</p>
                <a href="#sugerencias-planes" class="more-info-btn">»</a>
            </div>
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-tasks icon"></i></span>
                <h3>Añade tareas</h3>
                <p>Organiza y asigna tareas para el viaje.</p>
                <a href="#anade-tareas" class="more-info-btn">»</a>
            </div>
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-coins icon"></i></span>
                <h3>Control de gastos</h3>
                <p>Gestiona los gastos compartidos durante el viaje.</p>
                <a href="#control-gastos" class="more-info-btn">»</a>
            </div>
            <div class="feature-card">
                <span class="icon-circle"><i class="fas fa-clock icon"></i></span>
                <h3>Cronograma visual</h3>
                <p>Crea un cronograma detallado de tu viaje.</p>
                <a href="#cronograma-visual" class="more-info-btn">»</a>
            </div>
        </div>
    </section>

    <section id="map-destino" class="map-section">
        <h2 class="titulo-oscuro">Destinos Favoritos</h2>
        <p>Estos son algunos de los destinos más populares de nuestra aplicación.</p>
        <div class="map-container">
            <div id="map"></div>
        </div>
    </section>

    <section class="feature-details">
        <!-- Sección 1: Votación de Destinos (Fondo Blanco) -->
        <div id="votacion-destinos" class="feature-detail">
            <img src="../assets/images/votacion-destinos.webp" alt="Votación de Destinos" class="feature-image">
            <div class="feature-text">
                <h3>Votación de Destinos</h3>
                <p>Permite a los usuarios votar por sus destinos favoritos, lo que facilita la toma de decisiones en
                    grupo y asegura que todos los participantes tengan voz en el itinerario. Este sistema fomenta la
                    participación y el consenso, permitiendo a los usuarios seleccionar destinos en función de las
                    preferencias del grupo. Al involucrar a todos en el proceso, se minimizan los desacuerdos y se
                    aumenta la satisfacción con el destino final, asegurando una experiencia de viaje agradable para
                    todos.</p>
            </div>
        </div>

        <!-- Sección 2: Gestión de Actividades (Fondo Oscuro) -->
        <div id="gestiona-actividades" class="feature-detail reverse">
            <img src="../assets/images/gestiona-actividades.webp" alt="Gestión de Actividades" class="feature-image">
            <div class="feature-text">
                <h3>Gestión de Actividades</h3>
                <p>Organiza las actividades diarias de tu viaje para que cada día esté lleno de diversión y momentos
                    memorables. Planifica y coordina de manera eficaz para evitar momentos de incertidumbre. Este
                    sistema permite asignar tiempos específicos a cada actividad, proporcionando una estructura que
                    facilita a los miembros del grupo saber qué esperar y cuándo. Además, ayuda a evitar sobrecargar el
                    día y permite momentos de descanso o improvisación, logrando así un equilibrio perfecto entre
                    planificación y flexibilidad.</p>
            </div>
        </div>

        <!-- Sección 3: Sugerencias de Planes (Fondo Blanco) -->
        <div id="sugerencias-planes" class="feature-detail">
            <img src="../assets/images/sugerencias-planes.webp" alt="Sugerencias de Planes" class="feature-image">
            <div class="feature-text">
                <h3>Sugerencias de Planes</h3>
                <p>Recibe ideas de actividades y planes que se ajusten a tus intereses y al destino que hayas elegido,
                    haciendo que tu viaje sea único y emocionante. La plataforma analiza tus preferencias y ofrece
                    recomendaciones personalizadas, desde rutas culturales hasta lugares gastronómicos imperdibles. Esta
                    funcionalidad también permite descubrir lugares menos conocidos, brindando la posibilidad de vivir
                    una experiencia auténtica y fuera de lo común, perfecta para quienes buscan explorar en profundidad.
                </p>
            </div>
        </div>

        <!-- Sección 4: Añadir Tareas (Fondo Oscuro) -->
        <div id="anade-tareas" class="feature-detail reverse">
            <img src="../assets/images/anade-tareas.webp" alt="Añadir Tareas" class="feature-image">
            <div class="feature-text">
                <h3>Añadir Tareas</h3>
                <p>Organiza y asigna tareas para el viaje, como reservaciones, compras, y responsabilidades, asegurando
                    que todo esté en orden y cada miembro tenga una función. Esta funcionalidad permite dividir las
                    tareas entre los participantes, asignando responsabilidades claras para que cada aspecto del viaje
                    esté cubierto. Desde la reserva de alojamiento hasta la compra de boletos, esta herramienta asegura
                    que no haya detalles sin resolver, evitando así problemas de última hora y haciendo que el viaje
                    transcurra sin contratiempos.</p>
            </div>
        </div>

        <!-- Sección 5: Control de Gastos (Fondo Blanco) -->
        <div id="control-gastos" class="feature-detail">
            <img src="../assets/images/control-gastos.webp" alt="Control de Gastos" class="feature-image">
            <div class="feature-text">
                <h3>Control de Gastos</h3>
                <p>Gestiona los gastos compartidos entre los miembros del grupo, llevando un registro claro de quién
                    debe qué y evitando confusiones. Cada gasto queda registrado, permitiendo ver el balance de cada
                    miembro al final del viaje. Esta funcionalidad facilita la organización financiera, evitando
                    malentendidos y asegurando que cada persona sea responsable de sus contribuciones, lo cual permite
                    disfrutar del viaje sin preocupaciones y con total transparencia en la gestión de gastos
                    compartidos.</p>
            </div>
        </div>

        <!-- Sección 6: Cronograma Visual del Viaje (Fondo Oscuro) -->
        <div id="cronograma-visual" class="feature-detail reverse">
            <img src="../assets/images/cronograma-visual.webp" alt="Cronograma Visual del Viaje" class="feature-image">
            <div class="feature-text">
                <h3>Cronograma Visual del Viaje</h3>
                <p>Crea un cronograma visual detallado que muestre todas las actividades planeadas en orden. Esto ayuda
                    a mantener el viaje organizado y a que todos sepan qué esperar. El cronograma permite una visión
                    clara del flujo del viaje, mostrando en un solo vistazo los momentos destacados y el tiempo dedicado
                    a cada actividad. Además, brinda flexibilidad al permitir cambios de último minuto sin perder el
                    orden, asegurando que el grupo esté siempre sincronizado y optimizando el disfrute de cada
                    experiencia.</p>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-links">
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#funcionalidades">Funcionalidades</a></li>
                    <li><a href="#map-destino">Mapa Interactivo</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="dashboard.php">Mi Dashboard</a></li>
                    <?php else: ?>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-account">
                <h3>Cuenta</h3>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="footer-button connect">Dashboard</a>
                    <a href="logout.php" class="footer-button register">Cerrar Sesión</a>
                <?php else: ?>
                    <a href="auth_login.php" class="footer-button connect">Conectar</a>
                    <a href="auth_register.php" class="footer-button register">Registrarse</a>
                <?php endif; ?>
            </div>

            <div class="footer-about">
                <h2>RutaLibre</h2>
                <p>Organiza tus viajes y disfruta de una experiencia inolvidable. Explora destinos, gestiona actividades
                    y comparte gastos con facilidad.</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p>&copy; 2024 RutaLibre. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="../js/main.js"></script>

</body>

</html>