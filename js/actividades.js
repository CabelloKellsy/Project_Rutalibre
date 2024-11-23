// actividades.js

// Obtener el ID del viaje de la URL
function obtenerIdViajeDeURL() {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('id_viaje');
}
async function cargarActividades(idViaje) {
    if (!idViaje) {
        mostrarError('ID de viaje no especificado');
        return;
    }

    try {
        const response = await fetch(`../bd/actividades_usuario.php?viaje_id=${idViaje}`);
        if (!response.ok) {
            throw new Error('Error al cargar las actividades');
        }

        const data = await response.json();
        const contenedorActividades = document.getElementById('contenedorActividades');
        contenedorActividades.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Actividades del Viaje</h2>
                    <a href="dashboard.php" class="btn btn-link">
                        <i class="fas fa-arrow-left"></i> Volver a Viajes
                    </a>
                </div>
                <button onclick="mostrarModalActividad('crear', ${idViaje})" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Actividad
                </button>
            </div>
            <div id="listaActividades" class="row"></div>
        `;

        const listaActividades = document.getElementById('listaActividades');

        if (data.actividades && data.actividades.length > 0) {
            listaActividades.innerHTML = data.actividades.map(actividad => `
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">${actividad.nombre_actividad}</h5>
                            <p class="card-text">${actividad.descripcion}</p>
                            <div class="text-muted mb-3">
                                <div><i class="far fa-calendar-alt"></i> Inicio: ${formatearFecha(actividad.fecha_inicio)}</div>
                                <div><i class="far fa-calendar-alt"></i> Fin: ${formatearFecha(actividad.fecha_fin)}</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button onclick="mostrarModalActividad('editar', ${idViaje}, ${JSON.stringify(actividad).replace(/"/g, '&quot;')})" 
                                        class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                                <button onclick="eliminarActividad(${actividad.id_actividad}, ${idViaje})" 
                                        class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            listaActividades.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No hay actividades registradas para este viaje.
                        <p class="mt-3">
                            <button onclick="mostrarModalActividad('crear', ${idViaje})" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Actividad
                            </button>
                        </p>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        mostrarError('Error al cargar las actividades: ' + error.message);
    }
}

function mostrarModalActividad(modo, idViaje, actividadData = null) {
    const modal = new bootstrap.Modal(document.getElementById('modalActividad'));
    const modalTitle = document.getElementById('modalActividadLabel');
    const form = document.getElementById('formActividades');

    form.reset();
    document.getElementById('viajes_id_viajes').value = idViaje;

    if (modo === 'crear') {
        modalTitle.textContent = 'Nueva Actividad';
        form.dataset.modo = 'crear';

        // Establecer fechas por defecto
        const hoy = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').value = hoy;
        document.getElementById('fecha_fin').value = hoy;
    } else {
        modalTitle.textContent = 'Editar Actividad';
        form.dataset.modo = 'editar';
        form.dataset.idActividad = actividadData.id_actividad;

        document.getElementById('nombre_actividad').value = actividadData.nombre_actividad;
        document.getElementById('descripcion').value = actividadData.descripcion;
        document.getElementById('fecha_inicio').value = actividadData.fecha_inicio.split(' ')[0];
        document.getElementById('fecha_fin').value = actividadData.fecha_fin.split(' ')[0];
    }

    modal.show();
}

async function guardarActividad(event) {
    event.preventDefault();

    try {
        const form = event.target;
        const formData = new FormData(form);
        const modo = form.dataset.modo;
        const idViaje = document.getElementById('viajes_id_viajes').value;

        // Validar fechas
        const fechaInicio = new Date(formData.get('fecha_inicio'));
        const fechaFin = new Date(formData.get('fecha_fin'));

        if (fechaFin < fechaInicio) {
            throw new Error('La fecha de fin no puede ser anterior a la fecha de inicio');
        }

        // Validar campos requeridos
        if (!formData.get('nombre_actividad').trim()) {
            throw new Error('El nombre de la actividad es requerido');
        }
        if (!formData.get('descripcion').trim()) {
            throw new Error('La descripción es requerida');
        }

        let url = '../bd/actividades_usuario.php';
        let options = {
            method: 'POST',
            body: formData
        };

        if (modo === 'editar') {
            const id_actividad = form.dataset.idActividad;
            const data = Object.fromEntries(formData.entries());
            data.id_actividad = id_actividad;

            options = {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            };
            url += `?id_actividad=${id_actividad}`;
        }

        const response = await fetch(url, options);
        const data = await response.json();

        if (!response.ok || data.error) {
            throw new Error(data.error || 'Error en la operación');
        }

        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalActividad'));
        modal.hide();

        // Mostrar mensaje de éxito
        await Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: modo === 'crear' ? 'Actividad creada correctamente' : 'Actividad actualizada correctamente',
            showConfirmButton: false,
            timer: 1500
        });

        // Recargar actividades
        await cargarActividades(idViaje);

    } catch (error) {
        mostrarError(error.message);
    }
}

async function eliminarActividad(idActividad, idViaje) {
    try {
        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (result.isConfirmed) {
            const response = await fetch(`../bd/actividades_usuario.php?id_actividad=${idActividad}`, {
                method: 'DELETE'
            });

            const data = await response.json();

            if (!response.ok || data.error) {
                throw new Error(data.error || 'Error al eliminar la actividad');
            }

            await Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Actividad eliminada correctamente',
                showConfirmButton: false,
                timer: 1500
            });

            await cargarActividades(idViaje);
        }
    } catch (error) {
        mostrarError(error.message);
    }
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function mostrarError(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: mensaje,
        confirmButtonText: 'Aceptar'
    });
}

// Inicialización cuando el documento está listo
document.addEventListener('DOMContentLoaded', () => {
    const formActividades = document.getElementById('formActividades');
    if (formActividades) {
        formActividades.addEventListener('submit', guardarActividad);
    }

    // Cargar actividades automáticamente si hay un ID de viaje en la URL
    const idViaje = obtenerIdViajeDeURL();
    if (idViaje) {
        cargarActividades(idViaje);
    } else {
        mostrarError('No se especificó un ID de viaje válido');
    }
});