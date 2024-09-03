$(document).ready(function() {
    $('#codigo-pago').change(function() {
        var idCategoria = $(this).val();
        if (idCategoria != 0) {
            // Obtener categorías
            $.ajax({
                url: '../NuevoIngresoSolicitud/obtener_categoria.php',
                method: 'POST',
                data: { codigo_pago: idCategoria },
                dataType: 'json',
                success: function(response) {
                    $('#id_categoria').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });

            // Obtener tipos de solicitud
            $.ajax({
                url: '../NuevoIngresoSolicitud/obtener_tipo_solicitud.php',
                method: 'POST',
                data: { codigo_pago: idCategoria },
                dataType: 'json',
                success: function(response) {
                    $('#id_tipo_solicitud').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr);
                }
            });
        } else {
            $('#id_categoria').html('<option value="0">Seleccionar</option>');
            $('#id_tipo_solicitud').html('<option value="">Selecciona el tipo de solicitud</option>');
        }
    }); 
});
