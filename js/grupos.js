
// Variables globales
let currentViajeId = null;

// Cargar viajes al iniciar
document.addEventListener('DOMContentLoaded', loadViajes);

// Cargar lista de viajes
async function loadViajes() {
    try {
        const response = await fetch('../bd/grupos.php?action=viajes');
        const data = await response.json();
        const select = document.getElementById('viajeSelect');

        data.forEach(viaje => {
            const option = document.createElement('option');
            option.value = viaje.id_viajes;
            option.textContent = viaje.nombre_viaje;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error al cargar viajes:', error);
    }
}

// Event listener para el selector de viajes
document.getElementById('viajeSelect').addEventListener('change', async (e) => {
    currentViajeId = e.target.value;
    if (currentViajeId) {
        await loadGrupos(currentViajeId);
        document.getElementById('viajes_id_viajes').value = currentViajeId;
        document.getElementById('grupoForm').classList.remove('d-none');
    } else {
        document.getElementById('grupoForm').classList.add('d-none');
        document.getElementById('noGruposMessage').classList.add('d-none');
        document.querySelector('#gruposTable tbody').innerHTML = '';
    }
});

// Cargar grupos por viaje
async function loadGrupos(viajeId) {
    try {
        const response = await fetch(`../bd/grupos.php?viaje_id=${viajeId}`);
        const data = await response.json();
        const tbody = document.querySelector('#gruposTable tbody');
        const noGruposMessage = document.getElementById('noGruposMessage');

        tbody.innerHTML = '';

        if (data.grupos && data.grupos.length > 0) {
            noGruposMessage.classList.add('d-none');
            data.grupos.forEach(grupo => {
                const row = `
                    <tr>
                        <td>${grupo.id_grupo}</td>
                        <td>${grupo.nombre_grupo}</td>
                        <td>${grupo.integrantes}</td>
                        <td>${grupo.estado}</td>
                        <td>${grupo.descripcion}</td>
                        <td>
                            <button onclick="editGrupo(${JSON.stringify(grupo)})" class="btn btn-sm btn-warning">Editar</button>
                            <button onclick="deleteGrupo(${grupo.id_grupo})" class="btn btn-sm btn-danger">Eliminar</button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            noGruposMessage.classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error al cargar grupos:', error);
    }
}

// Manejar el envío del formulario
document.getElementById('groupForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = {
        nombre_grupo: document.getElementById('nombre_grupo').value,
        integrantes: document.getElementById('integrantes').value,
        estado: document.getElementById('estado').value,
        descripcion: document.getElementById('descripcion').value,
        viaje_id: currentViajeId
    };

    try {
        const response = await fetch('../bd/grupos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        if (response.ok) {
            alert('Grupo creado exitosamente');
            resetForm();
            loadGrupos(currentViajeId);
        } else {
            console.error('Error al crear el grupo:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error al crear el grupo:', error);
    }
});

// Función para editar un grupo
async function editGrupo(grupo) {
    try {
        const response = await fetch(`../bd/grupos.php?id_grupo=${grupo.id_grupo}`);
        const data = await response.json();

        if (response.ok) {
            document.getElementById('id_grupo').value = grupo.id_grupo;
            document.getElementById('nombre_grupo').value = grupo.nombre_grupo;
            document.getElementById('integrantes').value = grupo.integrantes;
            document.getElementById('estado').value = grupo.estado;
            document.getElementById('descripcion').value = grupo.descripcion;
            document.getElementById('viajes_id_viajes').value = grupo.viajes_id_viajes;
            document.getElementById('formTitle').textContent = 'Editar Grupo';
            document.getElementById('grupoForm').classList.remove('d-none');
        } else {
            console.error('Error al editar el grupo:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error al editar el grupo:', error);
    }
}

// Función para eliminar un grupo
async function deleteGrupo(id_grupo) {
    try {
        const response = await fetch(`../bd/grupos.php?id_grupo=${id_grupo}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            alert('Grupo eliminado exitosamente');
            loadGrupos(currentViajeId);
        } else {
            console.error('Error al eliminar el grupo:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error al eliminar el grupo:', error);
    }
}

// funcion para getGruposDeViajes ✅
function cargarGruposDeViajes(id) {
    fetch(`../bd/grupos.php?viaje_id=${id}`)
    .then(response => response.json())
    .then(grupodeviajes => {
        console.log(grupodeviajes);
    }).catch(error => console.error('Error:', error));
}

// Función para resetear el formulario
function resetForm() {
    document.getElementById('id_grupo').value = '';
    document.getElementById('nombre_grupo').value = '';
    document.getElementById('integrantes').value = '';
    document.getElementById('estado').value = 'Activo';
    document.getElementById('descripcion').value = '';
    document.getElementById('formTitle').textContent = 'Crear Grupo';
    document.getElementById('grupoForm').classList.add('d-none');
}
