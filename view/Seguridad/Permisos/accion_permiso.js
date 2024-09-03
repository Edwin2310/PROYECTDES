/* 

$(document).ready(function() {
    // Función para verificar permisos
    function verificarPermisos(id_rol, id_objeto, permiso, callback) {
        $.ajax({
            url: 'accion_permisos.php',
            type: 'POST',
            data: {
                id_rol: id_rol,
                id_objeto: id_objeto,
                permiso: permiso
            },
            success: function(response) {
                if (response.tiene_permiso) {
                    callback(true);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sin Permisos',
                        text: 'No tienes permisos para realizar esta acción.'
                    });
                    callback(false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Ocurrió un error al verificar los permisos:', xhr.responseText);
                callback(false);
            }
        });
    }

    // Evento click para los botones
    $('.accion-permiso').on('click', function(event) {
        event.preventDefault();

        const id_objeto = $(this).data('id-objeto');
        const permiso = $(this).data('permiso');
        const id_rol = <?php echo json_encode($id_rol); ?>;

        verificarPermisos(id_rol, id_objeto, permiso, function(tienePermiso) {
            if (tienePermiso) {
                // Aquí puedes agregar la acción que debe ejecutarse si tiene permisos
                // Ejemplo:
                if (permiso === 1) {
                    // Acción de inserción
                    console.log('Permiso de inserción concedido.');
                } else if (permiso === 2) {
                    // Acción de eliminación
                    console.log('Permiso de eliminación concedido.');
                }
            }
        });
    });
}); */


$(document).ready(function () {
    // Función para verificar permisos
    function verificarPermisos(id_rol, id_objeto, permiso, callback) {
        $.ajax({
            url: '../Seguridad/Permisos/accion_permisos.php',
            type: 'POST',
            data: {
                id_rol: id_rol,
                id_objeto: id_objeto,
                permiso: permiso
            },
            success: function (response) {
                const jsonResponse = JSON.parse(response);
                if (jsonResponse.tiene_permiso) {
                    callback(true);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sin Permisos',
                        text: 'No tienes permisos para realizar esta acción.'
                    });
                    callback(false);
                }
            },
            error: function (xhr, status, error) {
                console.error('Ocurrió un error al verificar los permisos:', xhr.responseText);
                callback(false);
            }
        });
    }

    // Obtener el id_rol del usuario desde el elemento oculto
    const idRolUsuario = $('#usuario-id-rol').val();

    // Evento click para los botones
    $('.accion-permiso').on('click', function (event) {
        event.preventDefault();

        const id_objeto = $(this).data('id-a-objeto');
        const permiso = $(this).data('permiso');
        const id_rol = idRolUsuario; // Usar el id_rol del usuario


        verificarPermisos(id_rol, id_objeto, permiso, function (tienePermiso) {
            if (tienePermiso) {
                // Aquí puedes agregar la acción que debe ejecutarse si tiene permisos
                // Ejemplo:
                if (permiso === 1) {
                    // Acción de inserción
                    console.log('Permiso de inserción concedido.');
                    // Enviar el formulario
                    $('#permisoForm').submit();
                } else if (permiso === 2) {
                    // Acción de eliminación
                    console.log('Permiso de eliminación concedido.');
                } else if (permiso === 3) {
                    // Acción de actualizacion
                    console.log('Permiso de actualizacion concedido.');
                } else if (permiso === 4) {
                    // Acción de consultar
                    console.log('Permiso de consultar concedido.');
                }
            }
        });
    });
});

