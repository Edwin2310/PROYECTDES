$(document).ready(function() {
    // Cuando se haga clic en el botón de verificar código
    $('form').submit(function(event) {
        event.preventDefault(); // Evitar el envío automático del formulario
        
        var codigo = $('#codigo').val(); // Obtener el valor del campo de código de validación
        var token = $('input[name="token"]').val(); // Obtener el valor del token del campo oculto
        
        // Validar que el código no esté vacío
        if (codigo.trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese el código de validación.',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            });
            return false; // Detener el proceso si el código está vacío
        }

         // Validar que el código solo contenga números
         if (!/^[0-9]+$/.test(codigo)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El código de validación solo debe contener números.',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            });
            return false; // Detener el proceso si el código contiene letras u otros caracteres
        }
        
        // Realizar la solicitud POST al servidor para verificar el código
        $.ajax({
            type: 'POST',
            url: 'validarCodigo.php',
            data: { token: token, codigo: codigo },
            dataType: 'json', // Esperar una respuesta de tipo JSON
            success: function(response) {
                if (response.status === 'success') {
                    // Mostrar mensaje de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: response.message, // Mensaje de éxito recibido del servidor
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar'
                    }).then(function() {
                        // Redirigir a la página de nueva contraseña después de mostrar el mensaje
                        setTimeout(function() {
                            window.location.href = 'nuevaContraseña.php?token=' + token + '&correo=' + response.correo;
                        }, 250); // Redirigir después de 2 segundos (2000 milisegundos)
                    });
                } else {
                    // Mostrar mensaje de error si la validación falla
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message, // Mensaje de error recibido del servidor
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Aceptar'
                    });
                }
            },
            error: function() {
                // Manejar errores de conexión u otros errores del servidor
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al intentar verificar el código.',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
});
