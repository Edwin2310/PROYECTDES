$(document).ready(function() {
    $('#btn-guardar-contrasena').click(function() {
        var nuevaContrasena = $('#nueva_contrasena').val();
        var confirmarContrasena = $('#confirmar_contrasena').val();

        // Validar que ambos campos estén completos
        if (nuevaContrasena.trim() === '' || confirmarContrasena.trim() === '') {
            mostrarError('Por favor, completa todos los campos.');
            return false;
        }

        // Validar longitud mínima de la contraseña
        if (nuevaContrasena.length < 8) {
            mostrarError('La contraseña debe tener al menos 8 caracteres.');
            return false;
        }

        // Validar que la contraseña contenga al menos una letra mayúscula, un número, un carácter especial y no contenga espacios entre letras
        if (!contieneMayusculaNumeroYEspecialSinEspacios(nuevaContrasena)) {
            mostrarError('La contraseña debe contener al menos una letra mayúscula, un número, un carácter especial (por ejemplo, @, #, $, %).');
            return false;
        }

        // Validar que las contraseñas coincidan
        if (nuevaContrasena !== confirmarContrasena) {
            mostrarError('Las contraseñas no coinciden.');
            return false;
        }

        // Mostrar mensaje de confirmación
        Swal.fire({
            icon: 'success',
            title: 'Contraseña Actualizada',
            text: 'Tu contraseña ha sido actualizada correctamente.',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar los datos del formulario con Ajax
                $.ajax({
                    type: 'POST',
                    url: './guardarNuevaContraseña.php',
                    data: $('#form-nueva-contrasena').serialize(),
                    success: function(response) {
                        // Mostrar mensaje de éxito si es necesario
                        console.log(response); // Para depuración, puedes quitar esta línea
                        // Redirigir al usuario al inicio de sesión
                        window.location.href = '../index.php'; // Cambia la ruta según tu estructura de archivos
                    },
                    error: function(xhr, status, error) {
                        // Manejo de errores si es necesario
                        console.error(xhr.responseText);
                    }
                });
            }
        });
    });

    // Función para mostrar mensajes de error con SweetAlert
    function mostrarError(mensaje) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: mensaje,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        });
    }

    // Función para validar que la contraseña cumple con los requisitos
    function contieneMayusculaNumeroYEspecialSinEspacios(password) {
        var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?\/\\~-])(?!.*\s)/;
        return regex.test(password);
    }
});
