

/* 
$(document).ready(function() {
    $('.delete-row').on('click', function() {
        var $row = $(this).closest('tr');
        var idRol = $row.data('id-rol');
        var idObjeto = $row.data('id-objeto');

        if (confirm('¿Está seguro de que desea eliminar esta fila?')) {
            $.ajax({
                url: '../Seguridad/Manpermisos/eliminar_pantalla.php',
                type: 'POST',
                data: {
                    id_rol: idRol,
                    id_objeto: idObjeto
                },
                success: function(response) {
                    if (response == 'success') {
                        $row.remove();
                    } else {
                        alert('Error al eliminar la fila.');
                    }
                }
            });
        }
    });
}); */



$(document).ready(function() {
    $('.delete-row').on('click', function() {
        var $row = $(this).closest('tr');
        var idRol = $row.data('id-rol');
        var idObjeto = $row.data('id-objeto');

       /*  console.log('ID NombreRol:', idRol); // Verificar en consola
        console.log('ID Objeto:', idObjeto); // Verificar en consola */

        Swal.fire({
            title: '¿Está seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../Seguridad/Manpermisos/eliminar_pantalla.php',
                    type: 'POST',
                    data: {
                        id_rol: idRol,
                        id_objeto: idObjeto
                    },
                    success: function(response) {
                      /*   console.log('Response:', response); */ // Depuración
                        if (response.trim() === 'success')  {
                            $row.remove();
                            Swal.fire(
                                'Eliminado!',
                                'La fila ha sido eliminada.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                'Hubo un problema al eliminar la fila.',
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
});