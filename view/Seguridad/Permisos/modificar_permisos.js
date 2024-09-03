
document.addEventListener('DOMContentLoaded', function () {
    var toggleButtons = document.querySelectorAll('.toggle-checkboxes');
    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var row = this.closest('tr');
            var checkboxes = row.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(function (checkbox) {
                checkbox.disabled = !checkbox.disabled;
            });

            // Recoger el id_rol y id_objeto desde el atributo data
            var idRol = row.getAttribute('data-id-rol');
            var idObjeto = row.getAttribute('data-id-objeto');

            // Recoger los datos de la fila y enviarlos al servidor
            var permisoInsercion = row.cells[1].querySelector('input').checked ? 1 : 0;
            var permisoEliminacion = row.cells[2].querySelector('input').checked ? 1 : 0;
            var permisoActualizacion = row.cells[3].querySelector('input').checked ? 1 : 0;
            var permisoConsultar = row.cells[4].querySelector('input').checked ? 1 : 0;

            var data = {
                idRol: idRol, 
                idObjeto: idObjeto,
                permisoInsercion: permisoInsercion,
                permisoEliminacion: permisoEliminacion,
                permisoActualizacion: permisoActualizacion,
                permisoConsultar: permisoConsultar
            };

            fetch('../Seguridad/Permisos/update_permisos.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }).then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        console.log('Success:', data.message);
                    } else {
                        console.error('Error:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        });
    });
});

