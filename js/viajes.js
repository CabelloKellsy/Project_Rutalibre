
const form = document.getElementById('viajeForm');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');
const cancelBtn = document.getElementById('cancelBtn');
const fechaInicio = document.getElementById('fecha_inicio');
const fechaFinal = document.getElementById('fecha_final');
let editingId = null;

// Configurar fecha mínima para los campos de fecha viajes general
function setupDateFields() {
    const today = new Date().toISOString().split('T')[0];
    fechaInicio.min = today;
    fechaFinal.min = today;

    // Evento para asegurar que fecha final no sea menor que fecha inicio viajes general
    fechaInicio.addEventListener('change', function () {
        fechaFinal.min = this.value;
        if (fechaFinal.value && fechaFinal.value < this.value) {
            fechaFinal.value = this.value;
        }
    });

    // Evento para asegurar que fecha inicio no sea mayor que fecha final viajes general
    fechaFinal.addEventListener('change', function () {
        if (fechaInicio.value && fechaInicio.value > this.value) {
            fechaInicio.value = this.value;
        }
    });
}

// Función para formatear fechas YYYY-MM-DD viajes general
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

// Cargar viajes viajes general
async function loadViajes() {
    try {
        const response = await fetch('../bd/viajes_insert.php');
        const viajes = await response.json();

        const tbody = document.getElementById('viajesTable');
        tbody.innerHTML = '';

        viajes.forEach(viaje => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${viaje.id_viaje}</td>
                <td>${viaje.nombre_viaje}</td>
                <td>${formatDate(viaje.fecha_inicio)}</td>
                <td>${formatDate(viaje.fecha_final)}</td>
                <td>${viaje.presupuesto_base}</td>
                <td>${viaje.estado}</td>
                <td>${viaje.id_usuario}</td>
                <td>${viaje.fecha_creacion}</td>

                <td>
                    <button onclick="editViaje(${viaje.id_viaje})" class="btn btn-sm btn-warning">Editar</button>
                    <button onclick="deleteViaje(${viaje.id_viaje})" class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        alert('Error al cargar los viajes: ' + error.message);
    }
}

// Resetear formulario viajes general
function resetForm() {
    form.reset();
    editingId = null;
    formTitle.textContent = 'Registrar Viaje';
    submitBtn.textContent = 'Guardar';
    cancelBtn.style.display = 'none';
    document.getElementById('id_viaje').value = '';
    setupDateFields(); // Restablecer las restricciones de fecha
}

// Manejar envío del formulario de viajes general
form.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const formData = new FormData(form);

        if (editingId) {
            // Actualizar
            const data = Object.fromEntries(formData.entries());
            const response = await fetch(`../bd/viajes_insert.php?id_viaje=${editingId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) throw new Error('Error al actualizar el viaje');
            alert('Viaje actualizado exitosamente');
        } else {
            // Crear nuevo
            const response = await fetch('../bd/viajes_insert.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error('Error al crear el viaje');
            alert('Viaje creado exitosamente');
        }

        resetForm();
        loadViajes();
    } catch (error) {
        alert(error.message);
    }
});

// Editar viaje  general
async function editViaje(id) {
    try {
        const response = await fetch(`../bd/viajes_insert.php?id_viaje=${id}`);
        const viajes = await response.json();
        const viaje = viajes.find(v => v.id_viaje == id);

        if (viaje) {
            editingId = id;
            formTitle.textContent = 'Editar Viaje';
            submitBtn.textContent = 'Actualizar';
            cancelBtn.style.display = 'inline-block';

            // Llenar el formulario con los datos del viaje
            document.getElementById('id_viaje').value = viaje.id_viaje;
            document.getElementById('nombre_viaje').value = viaje.nombre_viaje;
            document.getElementById('fecha_inicio').value = formatDate(viaje.fecha_inicio);
            document.getElementById('fecha_final').value = formatDate(viaje.fecha_final);
            document.getElementById('presupuesto_base').value = viaje.presupuesto_base;
            document.getElementById('estado').value = viaje.estado;
            document.getElementById('id_usuario').value = viaje.id_usuario;
            document.getElementById('fecha_creacion').value = viaje.fecha_creacion;

            // Hacer scroll al formulario
            form.scrollIntoView({ behavior: 'smooth' });
        }
    } catch (error) {
        alert('Error al cargar el viaje: ' + error.message);
    }
}

// Eliminar viaje de la lista viajes general
async function deleteViaje(id) {
    if (!confirm('¿Está seguro de eliminar este viaje?')) return;

    try {
        const response = await fetch(`../bd/viajes_insert.php?id_viaje=${id}`, {
            method: 'DELETE'
        });

        if (!response.ok) throw new Error('Error al eliminar el viaje');
        alert('Viaje eliminado exitosamente');
        loadViajes();
    } catch (error) {
        alert(error.message);
    }
}

function cargarProximosViajes(userId) {
    fetch(`../bd/viajes_usuario.php?userId=${userId}&method=getProximosViajes`)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(proximosViajes => {
            console.log('Response data:', proximosViajes);
            const tbody = document.getElementById('tablaProximosViajesBody');
            tbody.innerHTML = ''; // Limpiar tabla

            proximosViajes.forEach(viaje => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${viaje.id_viaje}</td>
                    <td>${viaje.nombre_viaje}</td>
                    <td>${formatDate(viaje.fecha_inicio)}</td>
                    <td>${formatDate(viaje.fecha_final)}</td>
                    <td>${viaje.presupuesto_base}</td>
                    <td>${viaje.estado}</td>
                    <td>${viaje.id_usuario}</td>
                    <td>${viaje.fecha_creacion}</td>
                    <td>
                        <button onclick="editViaje(${viaje.id_viaje})" class="btn btn-sm btn-warning">Editar</button>
                        <button onclick="deleteViaje(${viaje.id_viaje})" class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

function editViaje(idViaje) {
    console.log(`Editando viaje con ID: ${idViaje}`);
}

// Llamar a la función con el userId correspondiente después de que el usuario haya iniciado sesión
const userId = 13; // Este es un ejemplo; usa el userId real del usuario
cargarProximosViajes(userId);



// Cancelar edición de viajes general
cancelBtn.addEventListener('click', () => {
    resetForm();
});

// Inicializar campos de fecha y cargar viajes  general
setupDateFields();
loadViajes();
