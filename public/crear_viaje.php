<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Viajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h1 id="formTitle">Registrar Viaje</h1>

    <form id="viajeForm" class="mb-4">
        <input type="hidden" name="id_viaje" id="id_viaje">
        <div class="mb-3">
            <label class="form-label">Nombre del viaje:</label>
            <input type="text" class="form-control" id="nombre_viaje" name="nombre_viaje" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de inicio:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha final:</label>
            <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Presupuesto base:</label>
            <input type="number" class="form-control" id="presupuesto_base" name="presupuesto_base" required min="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Estado:</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="Planificado">Planificado</option>
                <option value="En Curso">En Curso</option>
                <option value="Finalizado">Finalizado</option>
            </select>
        </div>
        <div>
            <label class="form-label">Id del Usuario:</label>
            <input type="text" class="form-control" id="id_usuario" name="id_usuario" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de creación:</label>
            <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion" required>
        </div>
        <button type="submit" class="btn btn-primary" id="submitBtn">Guardar</button>
        <button type="button" class="btn btn-secondary" id="cancelBtn" style="display: none;">Cancelar</button>
    </form>

    <script src="../js/viajes.js"></script>
</body>

</html>