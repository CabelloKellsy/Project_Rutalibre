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


