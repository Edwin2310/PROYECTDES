

document.getElementById('marcarTodas').addEventListener('click', function () {
    var checkboxes = document.querySelectorAll('input[name="selectRow"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = true;
    });
});

document.getElementById('desmarcarTodas').addEventListener('click', function () {
    var checkboxes = document.querySelectorAll('input[name="selectRow"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = false;
    });
});

document.getElementById('asignarSesion').addEventListener('click', function () {
    var selected = [];
    var checkboxes = document.querySelectorAll('input[name="selectRow"]:checked');
    checkboxes.forEach(function (checkbox) {
        selected.push(checkbox.value);
    });
    if (selected.length === 0) {
        /* alert("Por favor seleccione al menos una solicitud."); */
        Swal.fire({
            icon: 'warning',
            title: 'No hay solicitudes seleccionadas',
            text: 'Por favor seleccione al menos una solicitud.'
        });
        return;
    }
    document.getElementById('selectedSolicitudes').value = selected.join(',');
    $('#asignarSesionModal').modal('show');
});

// Validación del formulario del modal y envío de datos al servidor
document.getElementById('asignarSesionForm').addEventListener('submit', function (event) {
    var input = document.getElementById('numeroSesion');
    if (!input.checkValidity()) {
        input.classList.add('is-invalid');
        event.preventDefault();
        event.stopPropagation();
    } else {
        input.classList.remove('is-invalid');
        event.preventDefault(); // Evitar el envío predeterminado

        var formData = {
            numeroSesion: input.value,
            selectedSolicitudes: document.getElementById('selectedSolicitudes').value
        };

        $.ajax({
            url: '../MantenimientoSolicitudes/procesar_asignacion.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                /*   alert(response); */
                $('#asignarSesionModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: '¡Asignado!',
                    text: 'Se añadió correctamente la sesión.',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    // Recargar la página después de cerrar la alerta
                    window.location.reload();
                });
            },
            error: function (xhr, status, error) {
                /*  alert('Hubo un error al procesar la solicitud: ' + error); */
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hubo un error al procesar la solicitud: ' + error
                });
            }
        });
    }
});