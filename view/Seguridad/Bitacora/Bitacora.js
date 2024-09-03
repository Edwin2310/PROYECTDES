
$(document).ready(function () {


    // Función para registrar acciones en la bitácora
    function registrarEnBitacora(id_objeto, accion, descripcion) {
        // Enviar datos a Insertar_Bitacora.php
        $.ajax({
            url: '../Seguridad/Bitacora/Insertar_Bitacora.php',
            type: 'POST',
            data: {
                id_objeto: id_objeto,
                accion: accion,
                descripcion: descripcion
            },
            success: function (response) {
                console.log('Datos insertados en la bitácora: ' + response);

            },
            error: function (xhr, status, error) {
                console.error('Ocurrió un error al insertar en la bitácora:', xhr.responseText);
            }
        });
    }

    // Evento click para los enlaces de módulo
    $(document).on('click', '.modulo-link', function (event) {

        event.preventDefault(); // Evitar la navegación inmediata

        const id_objeto = $(this).data('id-objeto');
   

        // Verificar si la página de acceso denegado ha sido visitada
        const denegadoVisitado = sessionStorage.getItem('denegadoVisitado');

        // Determinar la acción
        const accion = (denegadoVisitado === 'true') ? 'acceso denegado' : $(this).data('accion');


        const descripcion = $(this).find('span, p').text().trim();

             // Limpiar la marca de visita a la página de acceso denegado
             sessionStorage.removeItem('denegadoVisitado');


        // Registrar en la bitácora
        registrarEnBitacora(id_objeto, accion, descripcion);

        // Navegar al enlace después de registrar en la bitácora
        window.location.href = $(this).attr('href');
    });

});


