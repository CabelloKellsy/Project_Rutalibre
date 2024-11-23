// actividades.js

async function cargarActividades(idViaje) {
    try {
        const response = await fetch(`../bd/actividades_usuario.php?viaje_id=${idViaje}`);

        if (!response.ok) {
            throw new Error('Error al cargar las actividades');
        }

        const data = await response.json();

        // Get the container where activities will be displayed
        const contenedorActividades = document.getElementById('contenedorActividades');
        contenedorActividades.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Actividades del Viaje</h2>
                <button onclick="mostrarModalActividad('crear', ${idViaje})" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Actividad
                </button>
            </div>
            <div id="listaActividades" class="row"></div>
        `;

        const listaActividades = document.getElementById('listaActividades');

        if (data.actividades && data.actividades.length > 0) {
            data.actividades.forEach(actividad => {
                const actividadHTML = `
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
                                    <button onclick="mostrarModalActividad('editar', ${idViaje}, ${JSON.stringify(actividad)})" 
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
                `;
                listaActividades.innerHTML += actividadHTML;
            });
        } else {
            listaActividades.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No hay actividades registradas para este viaje.
                    </div>
                </div>
            `;
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al cargar las actividades: ' + error.message,
            confirmButtonText: 'Aceptar'
        });
    }
}

function mostrarModalActividad(modo, idViaje, actividadData = null) {
    const modal = new bootstrap.Modal(document.getElementById('modalActividad'));
    const modalTitle = document.getElementById('modalActividadLabel');
    const form = document.getElementById('formActividades');

    // Establecer el ID del viaje en un campo oculto
    document.getElementById('viajes_id_viajes').value = idViaje;

    if (modo === 'crear') {
        modalTitle.textContent = 'Nueva Actividad';
        form.reset();
        form.dataset.modo = 'crear';
    } else {
        modalTitle.textContent = 'Editar Actividad';
        form.dataset.modo = 'editar';
        form.dataset.idActividad = actividadData.id_actividad;

        // Cargar datos de la actividad en el formulario
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
        const form = document.getElementById('formActividades');
        const formData = new FormData(form);
        const modo = form.dataset.modo;
        const idViaje = document.getElementById('viajes_id_viajes').value;

        let url = '../bd/actividades_usuario.php';
        let method = 'POST';

        if (modo === 'editar') {
            formData.append('id_actividad', form.dataset.idActividad);
            url += `?id_actividad=${form.dataset.idActividad}`;
            method = 'PUT';
        }

        const response = await fetch(url, {
            method: method,
            body: formData
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: modo === 'crear' ? 'Actividad creada correctamente' : 'Actividad actualizada correctamente',
            showConfirmButton: false,
            timer: 1500
        });

        // Cerrar modal y recargar actividades
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalActividad'));
        modal.hide();
        await cargarActividades(idViaje);

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            confirmButtonText: 'Aceptar'
        });
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

            if (!response.ok) {
                throw new Error('Error al eliminar la actividad');
            }

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: 'Actividad eliminada correctamente',
                showConfirmButton: false,
                timer: 1500
            });

            await cargarActividades(idViaje);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message,
            confirmButtonText: 'Aceptar'
        });
    }
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Inicializar los event listeners cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
    const formActividades = document.getElementById('formActividades');
    if (formActividades) {
        formActividades.addEventListener('submit', guardarActividad);
    }
});