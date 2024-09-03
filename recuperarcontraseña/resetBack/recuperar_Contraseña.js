$(document).ready(function() {
    $(document).on("click", "#btnrecuperar", function() {
        var correo = $('#correo').val();

        if (correo == "") {
            Swal.fire(
                'Dirección de Educación Superior',
                'Error: campo vacío',
                'error'
            );
        } else {
            // Mostrar indicador de carga
            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                }
            });

            // Realizar la solicitud para verificar el correo
            $.post("../controller/usuario.php?op=correo", { correo: correo }, function(response) {
                var data = JSON.parse(response);

                if (data.status === "error") {
                    Swal.close();
                    Swal.fire(
                        'Dirección de Educación Superior',
                        data.message,
                        'error'
                    );
                } else {
                    // Realizar la solicitud para enviar el correo de recuperación
                    $.post("../controller/email.php?op=send_recuperar", { correo: correo }, function(response) {
                        // Limpiar el campo de correo electrónico
                        $('#correo').val('');

                        // Cerrar el indicador de carga y mostrar mensaje de éxito
                        Swal.close();
                        Swal.fire(
                            'Dirección de Educación Superior',
                            'Se le ha enviado un correo para restablecer su contraseña',
                            'success'
                        );
                    });
                }
            });
        }
    });
});
