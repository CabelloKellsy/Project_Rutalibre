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

// Llamar a la función con el userId correspondiente después de que el usuario haya iniciado sesión
cargarProximosViajes(userId);




async function editViaje(idViaje) {
    try {
        const response = await fetch(`../bd/viajes_insert.php?id_viaje=${idViaje}`);
        const viajes = await response.json();
        const viaje = viajes.find(v => v.id_viaje == idViaje);

        if (viaje) {
            // Mostrar el formulario de edición
            document.getElementById('editViajeFormContainer').style.display = 'block';
            document.getElementById('id_viaje').value = viaje.id_viaje;
            document.getElementById('nombre_viaje').value = viaje.nombre_viaje;
            document.getElementById('fecha_inicio').value = formatDate(viaje.fecha_inicio);
            document.getElementById('fecha_final').value = formatDate(viaje.fecha_final);
            document.getElementById('presupuesto_base').value = viaje.presupuesto_base;
            document.getElementById('estado').value = viaje.estado;
        }
    } catch (error) {
        alert('Error al cargar el viaje: ' + error.message);
    }
}


//cargarProximosViajes(idViaje);
// Cancelar la edición y ocultar el formulario

document.getElementById('cancelEditBtn').addEventListener('click', function () {
    document.getElementById('editViajeFormContainer').style.display = 'none';
});


// Función para formatear la fecha en el formato adecuado
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toISOString().split('T')[0]; // Devuelve la fecha en formato YYYY-MM-DD
}


document.getElementById('editViajeForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Evita el envío del formulario por defecto

    const formData = new FormData(this);
    try {
        const response = await fetch('../bd/viajes_usuario.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        if (result.success) {
            alert('Viaje actualizado con éxito');
            // Aquí puedes actualizar la tabla o recargar los viajes
            document.getElementById('editViajeFormContainer').style.display = 'none'; cargarProximosViajes(userId);// Recargar la lista de viajes
        } else {
            alert('Error al actualizar el viaje: ' + result.message);

        }
    } catch (error) {
        alert('Error al enviar los datos: ' + error.message);
    }
});


function deleteViaje(idViaje) {
    if (confirm(`¿Estás seguro de que deseas eliminar el viaje con ID: ${idViaje}?`)) {
        fetch(`../bd/viajes_usuario.php?userId=${userId}&method=deleteViaje&id_viaje=${idViaje}`, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json' }
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Viaje eliminado con éxito');
                    cargarProximosViajes(userId); // Recargar la lista de viajes
                } else {
                    alert('Error al eliminar el viaje: ' + result.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
