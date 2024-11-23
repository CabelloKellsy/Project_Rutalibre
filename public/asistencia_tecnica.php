<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencia Técnica - RutaLibre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .faq-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .faq-item:hover {
            background-color: #f8f9fa;
        }

        .contact-section {
            margin-top: 30px;
        }

        .contact-section h2 {
            margin-bottom: 20px;
        }

        .back-button {
            margin-top: 20px;
            text-align: center;
        }

        .back-button a {
            text-decoration: none;
        }

        footer .back-to-home {
            color: #007bff;
            text-decoration: none;
        }

        footer .back-to-home:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header class="bg-primary text-white text-center py-4">
        <h1>Asistencia Técnica</h1>
        <p>Encuentra respuestas a tus preguntas y contáctanos para más ayuda</p>
    </header>

    <div class="container mt-5">
        <!-- Botón de Volver al Inicio -->
        <div class="back-button">
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Inicio
            </a>
        </div>

        <!-- Sección de preguntas frecuentes -->
        <section>
            <h2 class="mb-4">Preguntas Frecuentes</h2>
            <div class="faq-item" data-bs-toggle="collapse" data-bs-target="#faq1">
                <h5>¿Cómo puedo registrar un viaje?</h5>
                <div id="faq1" class="collapse">
                    Para registrar un viaje, ve a tu <strong>Dashboard</strong> y haz clic en el botón "Añadir un viaje". Llena los detalles requeridos y guarda los cambios.
                </div>
            </div>

            <div class="faq-item" data-bs-toggle="collapse" data-bs-target="#faq2">
                <h5>¿Cómo puedo editar o eliminar un viaje?</h5>
                <div id="faq2" class="collapse">
                    En el Dashboard, selecciona el viaje que deseas editar o eliminar. Haz clic en el botón correspondiente y sigue las instrucciones.
                </div>
            </div>

            <div class="faq-item" data-bs-toggle="collapse" data-bs-target="#faq3">
                <h5>¿Cómo puedo restablecer mi contraseña?</h5>
                <div id="faq3" class="collapse">
                    En la página de inicio de sesión, haz clic en "¿Olvidaste tu contraseña?" e ingresa tu correo electrónico. Recibirás instrucciones para restablecerla.
                </div>
            </div>

            <div class="faq-item" data-bs-toggle="collapse" data-bs-target="#faq4">
                <h5>¿Qué debo hacer si encuentro un error en la aplicación?</h5>
                <div id="faq4" class="collapse">
                    Si encuentras un error, por favor utiliza el formulario de contacto a continuación para informarnos. Describe el problema lo más detalladamente posible.
                </div>
            </div>
        </section>

        <!-- Formulario de contacto -->
        <section class="contact-section">
            <h2>Formulario de Contacto</h2>
            <p>Si no encontraste la respuesta a tu pregunta, contáctanos y te ayudaremos lo antes posible.</p>
            <form action="enviar_consulta.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </form>
        </section>
    </div>

    <footer class="bg-light text-center py-4 mt-5">
        <p>&copy; 2024 RutaLibre. Todos los derechos reservados.</p>
        <a href="index.php" class="back-to-home"><i class="fas fa-home"></i> Volver al Inicio</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
