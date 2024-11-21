document.addEventListener('DOMContentLoaded', () => {
    const usuarioForm = document.getElementById('usuarioForm');
    const usuariosTable = document.querySelector('#usuariosTable tbody');
    const viajesTable = document.querySelector('#viajesTable tbody');
    const gruposTable = document.querySelector('#gruposTable tbody');

    // Mostrar pestaña activa
    window.showTab = function (tabName) {
        // Ocultar todos los tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });

        // Mostrar el tab seleccionado
        document.getElementById(tabName).classList.add('active');

        // Cargar datos específicos de los tabs
        switch (tabName) {
            case 'usuarios':
                cargarUsuarios();
                break;
            case 'mis-viajes':
                cargarViajes();
                break;
            case 'mis-grupos':
                cargarGrupos();
                break;
        }
    };

    // Cargar usuarios
    function cargarUsuarios() {
        fetch('../bd/usuarios.php') // Solo esta ruta
            .then(response => response.json())
            .then(usuarios => {
                usuariosTable.innerHTML = ''; // Limpiar tabla
                usuarios.forEach(usuario => {
                    const row = `
                        <tr>
                            <td>${usuario.id_usuario}</td>
                            <td>${usuario.nombre}</td>
                            <td>${usuario.apellido}</td>
                            <td>${usuario.email}</td>
                            <td>${usuario.tipo_usuario}</td>
                            <td>
                                <button onclick="editarUsuario(${usuario.id_usuario})">Editar</button>
                                <button onclick="eliminarUsuario(${usuario.id_usuario})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    usuariosTable.innerHTML += row;
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Crear/Actualizar usuario
    usuarioForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const id = document.getElementById('usuarioId').value;
        const method = id ? 'PUT' : 'POST';
        const url = '../bd/usuarios.php' + (id ? `?id=${id}` : ''); // Solo esta ruta

        const data = {
            nombre: document.getElementById('nombre').value,
            apellido: document.getElementById('apellido').value,
            email: document.getElementById('email').value,
            tipo_usuario: document.getElementById('tipoUsuario').value,
            password: document.getElementById('password').value,
        };

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(() => {
                alert('Usuario guardado exitosamente');
                cargarUsuarios();
            })
            .catch(error => console.error('Error:', error));
    });

    // Editar usuario
    function editarUsuario(id) {
        fetch(`../bd/usuarios.php?id=${id}`)
            .then(response => response.json())
            .then(usuario => {
                document.getElementById('usuarioId').value = usuario.id_usuario;
                document.getElementById('nombre').value = usuario.nombre;
                document.getElementById('apellido').value = usuario.apellido;
                document.getElementById('email').value = usuario.email;
                document.getElementById('tipoUsuario').value = usuario.tipo_usuario;
                document.getElementById('password').value = '';
            })
            .catch(error => console.error('Error:', error));
    }

    // Eliminar usuario
    function eliminarUsuario(id) {
        if (confirm(`¿Estás seguro de eliminar el usuario con ID ${id}?`)) {
            fetch(`../bd/usuarios.php?id=${id}`, {
                method: 'DELETE'
            })
                .then(response => response.json())
                .then(() => {
                    alert('Usuario eliminado exitosamente');
                    cargarUsuarios();
                })
                .catch(error => console.error('Error:', error));
        }
    }

    // Cargar viajes de un usuario ✅
    function verificarViajesYGrupos() {
        fetch(`../bd/usuarios.php/viajes?usuario_id=${userId}`)
        .then(response => response.json())
        .then(viajes => {
            console.log(viajes); // Aquí puedes manejar la respuesta y mostrar los viajes
        }).catch(error => console.error('Error:', error));
    }


    // Cargar grupos de un usuario ✅
    function cargarGruposDeUsuario(userId) {
        fetch(`../bd/usuarios.php/grupos?usuario_id=${userId}`)
            .then(response => response.json())
            .then(grupos => {
                console.log(grupos); // Aquí puedes manejar la respuesta y mostrar los grupos
                // Por ejemplo, agregar los grupos a una tabla en el HTML
            }).catch(error => console.error('Error:', error));
    }

});
