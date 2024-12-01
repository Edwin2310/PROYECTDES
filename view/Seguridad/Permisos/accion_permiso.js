
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

    // Función para registrar en la bitácora
    function registrarEnBitacora(id_usuario, id_objeto, accion) {
        $.ajax({
            url: '../Seguridad/Bitacora/Insertar_Bitacora.php', // Ruta de tu archivo PHP para insertar la bitácora
            type: 'POST',
            data: {
                id_usuario: id_usuario,
                id_objeto: id_objeto,
                accion: accion
            },
            success: function (response) {
                console.log('Bitácora registrada con éxito:', response);
            },
            error: function (xhr, status, error) {
                console.error('Error al registrar en la bitácora:', xhr.responseText);
            }
        });
    }

    // Obtener el id_rol del usuario desde el elemento oculto
    const idRolUsuario = $('#usuario-id-rol').val();
    const idUsuario = $('#usuario-id').val(); // Suponiendo que el id del usuario está en un campo oculto

    // Evento click para los botones
    $('.accion-permiso').on('click', function (event) {
        event.preventDefault();

        const id_objeto = $(this).data('id-a-objeto');
        const permiso = $(this).data('permiso');
        const id_rol = idRolUsuario; // Usar el id_rol del usuario
        const id_usuario = idUsuario;


        verificarPermisos(id_rol, id_objeto, permiso, function (tienePermiso) {
            if (tienePermiso) {
                // Aquí puedes agregar la acción que debe ejecutarse si tiene permisos
                // Ejemplo:
                if (permiso === 1) {
                    // Acción de inserción
                    console.log('Permiso de inserción concedido.');
                    // Enviar el formulario
                    $('#permisoForm').submit();
                    registrarEnBitacora(id_usuario, id_objeto, 'Inserción realizada');
                            // Realizar la acción de inserción
                } else if (permiso === 2) {
                    // Acción de eliminación
                    console.log('Permiso de eliminación concedido.');
                    registrarEnBitacora(id_usuario, id_objeto, 'Eliminación realizada');
                    // Realizar la acción de eliminación
                } else if (permiso === 3) {
                    // Acción de actualizacion
                    console.log('Permiso de actualizacion concedido.');
                    registrarEnBitacora(id_usuario, id_objeto, 'Actualización realizada');
                    // Realizar la acción de actualización
                } else if (permiso === 4) {
                    // Acción de consultar
                    console.log('Permiso de consultar concedido.');
                    registrarEnBitacora(id_usuario, id_objeto, 'Actualización realizada');
                    // Realizar la acción de actualización
                }
            }
        });
    });
});

