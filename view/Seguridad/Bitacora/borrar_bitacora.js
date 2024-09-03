
$(document).ready(function() {
    $('#borrarBitacora').click(function() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede revertir! ¿Estás seguro de que deseas borrar todos los registros de la bitácora?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar todo'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../Seguridad/Bitacora/Borrar_bitacora.php',
                    type: 'POST',
                    data: { borrar: true },
                    success: function(response) {
                        Swal.fire(
                            '¡Borrado!',
                            'Todos los registros de la bitácora han sido eliminados.',
                            'success'
                        );
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error',
                            'Hubo un problema al intentar borrar los registros de la bitácora.',
                            'error'
                        );
                        console.error(error);
                    }
                });
            }
        });
    });
});
