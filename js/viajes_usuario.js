async function cargarProximosViajes(userId) {
    try {
        const response = await fetch(`../bd/viajes_usuario.php?userId=${userId}&method=getProximosViajes`);
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        const proximosViajes = await response.json();
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
                <td>
                    <button onclick="añadiractividades(${viaje.id_viaje})" class="btn btn-sm btn-danger">Añadir actividades</button
                    <button onclick="editViaje(${viaje.id_viaje})" class="btn btn-sm btn-warning">Editar</button>
                    <button onclick="deleteViaje(${viaje.id_viaje})" class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

async function cargarViajesAnteriores(userId) {
    try {
        const response = await fetch(`../bd/viajes_usuario.php?userId=${userId}&method=getViajesAnteriores`);
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        const viajesAnteriores = await response.json();
        const tbody = document.getElementById('tablaViajesAnterioresBody');
        tbody.innerHTML = ''; // Limpiar tabla

        viajesAnteriores.forEach(viaje => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${viaje.id_viaje}</td>
                <td>${viaje.nombre_viaje}</td>
                <td>${formatDate(viaje.fecha_inicio)}</td>
                <td>${formatDate(viaje.fecha_final)}</td>
                <td>${viaje.presupuesto_base}</td>
                <td>${viaje.estado}</td>
                <td>
                    <button onclick="deleteViaje(${viaje.id_viaje})" class="btn btn-sm btn-danger">Eliminar</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

async function editViaje(idViaje) {
    try {
        const response = await fetch(`../bd/viajes_insert.php?id_viaje=${idViaje}`);
        const viajes = await response.json();
        const viaje = viajes.find(v => v.id_viaje == idViaje);

        if (viaje) {
            document.getElementById('editViajeFormContainer').style.display = 'block';
            document.getElementById('id_viaje').value = viaje.id_viaje;
            document.getElementById('nombre_viaje').value = viaje.nombre_viaje;
            document.getElementById('fecha_inicio').value = formatDate(viaje.fecha_inicio);
            document.getElementById('fecha_final').value = formatDate(viaje.fecha_final);
            document.getElementById('presupuesto_base').value = viaje.presupuesto_base;

            // Establecer el estado a "planificado" por defecto si no tiene valor
            document.getElementById('estado').value = viaje.estado || 'planificado';

            // Establecer el atributo min para evitar fechas pasadas
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fecha_inicio').setAttribute('min', today);
            document.getElementById('fecha_final').setAttribute('min', today);
        }
    } catch (error) {
        alert('Error al cargar el viaje: ' + error.message);
    }
}

document.getElementById('cancelEditBtn').addEventListener('click', function () {
    document.getElementById('editViajeFormContainer').style.display = 'none';
});

document.getElementById('editViajeForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    const formData = new FormData(this);
    try {
        const today = new Date().toISOString().split('T')[0];
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFinal = document.getElementById('fecha_final').value;

        if (fechaInicio < today || fechaFinal < today) {
            alert('Las fechas no pueden ser menores a la fecha de hoy.');
            return; // Salir de la función si las fechas no son válidas
        }

        const response = await fetch('../bd/viajes_usuario.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('Viaje actualizado con éxito');
            location.reload(); // Recargar la página
        } else {
            alert('Error al actualizar el viaje: ' + result.message);
        }
    } catch (error) {
        alert('Error al enviar los datos: ' + error.message);
    }
});


async function deleteViaje(idViaje) {
    if (confirm(`¿Estás seguro de que deseas eliminar el viaje con ID: ${idViaje}?`)) {
        try {
            const response = await fetch(`../bd/viajes_usuario.php?userId=${userId}&method=deleteViaje&id_viaje=${idViaje}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' }
            });
            const result = await response.json();
            if (result.success) {
                alert('Viaje eliminado con éxito');
                location.reload(); // Recargar la página
            } else {
                alert('Error al eliminar el viaje: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}
