
const form = document.getElementById('viajeForm');
const formTitle = document.getElementById('formTitle');
const submitBtn = document.getElementById('submitBtn');
const cancelBtn = document.getElementById('cancelBtn');
const fechaInicio = document.getElementById('fecha_inicio');
const fechaFinal = document.getElementById('fecha_final');
let editingId = null;

// Configurar fecha mínima para los campos de fecha
function setupDateFields() {
    const today = new Date().toISOString().split('T')[0];
    fechaInicio.min = today;
    fechaFinal.min = today;

    // Evento para asegurar que fecha final no sea menor que fecha inicio
    fechaInicio.addEventListener('change', function () {
        fechaFinal.min = this.value;
        if (fechaFinal.value && fechaFinal.value < this.value) {
            fechaFinal.value = this.value;
        }
    });

    // Evento para asegurar que fecha inicio no sea mayor que fecha final
    fechaFinal.addEventListener('change', function () {
        if (fechaInicio.value && fechaInicio.value > this.value) {
            fechaInicio.value = this.value;
        }
    });
}

// Función para formatear fechas YYYY-MM-DD
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0];
}

// Cargar viajes
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

// Resetear formulario
function resetForm() {
    form.reset();
    editingId = null;
    formTitle.textContent = 'Registrar Viaje';
    submitBtn.textContent = 'Guardar';
    cancelBtn.style.display = 'none';
    document.getElementById('id_viaje').value = '';
    setupDateFields(); // Restablecer las restricciones de fecha
}

// Manejar envío del formulario
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

// Editar viaje
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

            // Hacer scroll al formulario
            form.scrollIntoView({ behavior: 'smooth' });
        }
    } catch (error) {
        alert('Error al cargar el viaje: ' + error.message);
    }
}

// Eliminar viaje
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

// Cancelar edición
cancelBtn.addEventListener('click', () => {
    resetForm();
});

// Inicializar campos de fecha y cargar viajes
setupDateFields();
loadViajes();
