//Parte de la DGCA

//dropzone dowlander
document.addEventListener('DOMContentLoaded', function () {
    // Obtener el ID de solicitud de los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const solicitudId = urlParams.get('id'); // Obtiene el valor del parámetro 'id' de la URL

    if (solicitudId) {
        fetch(`DocumentosOR.php?id=${solicitudId}`)
            .then(response => response.json())
            .then(file => {
                if (file.name && file.url) {
                    const fileListContainer = document.getElementById('file-list');
                    const fileDiv = document.createElement('div');
                    fileDiv.classList.add('dz-preview');

                    // Determinar el tipo de ícono basado en la extensión del archivo
                    let icon = '';
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    if (fileExtension === 'pdf') {
                        icon = 'fa fa-file-pdf-o'; // Ícono para PDF
                    } else if (fileExtension === 'doc' || fileExtension === 'docx') {
                        icon = 'fa fa-file-word-o'; // Ícono para Word
                    } else {
                        icon = 'fa fa-file'; // Ícono genérico para otros tipos de archivos
                    }

                    // Crear la estructura del archivo
                    fileDiv.innerHTML = `
                    <div class="file-icon"><i class="${icon}"></i></div> <!-- Ícono del archivo -->
                    <span>${file.name}</span>
                    <a href="${file.url}" download>Descargar archivo</a>`;
                    fileListContainer.appendChild(fileDiv);
                }
            })
            .catch(error => {
                console.error('Error al cargar los archivos:', error);
            });
    }
});

//atras
document.getElementById('backButton').addEventListener('click', function () {
    window.history.back();
});

//boton rachazo
document.getElementById('btn-rechazar').addEventListener('click', function () {
    var idSolicitud = new URLSearchParams(window.location.search).get('id');

    // Crear un nuevo objeto FormData para la solicitud
    var formData = new FormData();
    formData.append('idSolicitud', idSolicitud);
    formData.append('nuevoEstado', 12); // Establecer el nuevo estado a 18

    // Realizar la solicitud AJAX para actualizar el estado
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "AprobacionyRechazo.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Se mando a Subsanar el documento.'
                    }).then(() => {
                        window.location.href = 'AnalisisCurricular_RYAOR.php'; // Redirigir a la página deseada
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al actualizar el estado.'
                });
            }
        }
    };

    xhr.send(formData); // Enviar el FormData con el ID de solicitud y el nuevo estado
});

//boton aprobado
document.getElementById('btn-aprobado').addEventListener('click', function () {
    var idSolicitud = new URLSearchParams(window.location.search).get('id');

    // Crear un nuevo objeto FormData para la solicitud
    var formData = new FormData();
    formData.append('idSolicitud', idSolicitud);
    formData.append('nuevoEstado', 20); // Establecer el nuevo estado a 20

    // Realizar la solicitud AJAX para actualizar el estado
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "AprobacionyRechazo.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Aprobación completada. La Opinion Razonada ha sido aceptada.'
                    }).then(() => {
                        window.location.href = 'AnalisisCurricular_RYAOR.php'; // Redirigir a la página deseada
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al actualizar el estado.'
                });
            }
        }
    };

    xhr.send(formData); // Enviar el FormData con el ID de solicitud y el nuevo estado
});

