Dropzone.autoDiscover = false;

var idSolicitud = new URLSearchParams(window.location.search).get('id'); // Obtener ID de la URL
var filesToUpload = []; // Array para almacenar los archivos

var myDropzone = new Dropzone("#dropzone-documents", {
    url: "UploadObservaciones.php",
    dictDefaultMessage: "Arrastra y suelta un documento aquí",
    dictRemoveFile: "Eliminar archivo",
    acceptedFiles: ".pdf,.doc,.docx,.xls",
    maxFiles: 1,
    maxFilesize: 10, // Tamaño máximo del archivo en MB
    addRemoveLinks: true,
    autoProcessQueue: false, // Evita que se envíe automáticamente
    init: function () {
        var self = this;

        this.on("sending", function (file, xhr, formData) {
            formData.append("idSolicitud", idSolicitud); // Añadir idSolicitud a la solicitud
        });

        this.on("error", function (file, response) {
            console.log('Error:', response); // Para depuración
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Ocurrió un error al subir el archivo.',
            });
        });

        this.on("maxfilesexceeded", function (file) {
            Swal.fire({
                icon: 'warning',
                title: 'Límite de archivos alcanzado',
                text: 'Solo se puede subir un archivo. Por favor, elimina el archivo actual antes de subir uno nuevo.',
            });
            this.removeFile(file);
        });

        this.on("addedfile", function (file) {
            if (self.files.length > 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Solo se puede subir un archivo',
                    text: 'Ya has cargado un archivo.',
                    confirmButtonText: 'Entendido'
                });
                self.removeFile(file);
            } else {
                var fileName = file.name;
                var now = new Date();
                var dateStr =
                    now.getFullYear() +
                    (now.getMonth() + 1).toString().padStart(2, "0") +
                    now.getDate().toString().padStart(2, "0");
        
                // Cambiar el formato esperado a OBSDOC
                var expectedFormat = `OBSDOC_${dateStr}`;
                var regex = new RegExp(`^${expectedFormat}(\\d{3})?\\.`); // Permite nombres con o sin números de tres dígitos
        
                console.log("Formato esperado: " + expectedFormat);
        
                if (!regex.test(fileName)) {
                    self.removeFile(file);
                    Swal.fire({
                        icon: 'error',
                        title: 'Nombre de archivo no válido',
                        text: `Formato de nombre de archivo no válido. Ejemplo permitido: ${expectedFormat}`,
                        confirmButtonText: 'Entendido'
                    });
                } else {
                    filesToUpload.push(file); // Guardar archivo en el array
                }
            }
        });
        

        this.on("removedfile", function (file) {
            console.log('Archivo eliminado:', file);
            var index = filesToUpload.indexOf(file);
            if (index > -1) {
                filesToUpload.splice(index, 1); // Eliminar archivo del array
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const fileListContainer = document.getElementById('file-list');
    const urlParams = new URLSearchParams(window.location.search);
    const solicitudId = urlParams.get('id'); 

    if (solicitudId) {
        fetch(`list_sub.php?id=${solicitudId}`)
            .then(response => response.json())
            .then(files => {
                if (Array.isArray(files) && files.length > 0) {
                    files.forEach(file => {
                        if (file.name && file.url) {
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

                            fileDiv.innerHTML = `
                                <div class="file-icon"><i class="${icon}"></i></div>
                                <div class="file-details">
                                    <span class="file-name">${file.name}</span>
                                    <a href="${file.url}" download class="file-download">Descargar archivo</a>
                                </div>`;
                            fileListContainer.appendChild(fileDiv);
                        }
                    });
                } else {
                    fileListContainer.innerHTML = '<p>No se encontraron archivos.</p>';
                }
            })
            .catch(error => {
                console.error('Error al cargar los archivos:', error);
                fileListContainer.innerHTML = '<p>Error al cargar los archivos.</p>';
            });
    } else {
        console.error('No se proporcionó un ID de solicitud.');
        fileListContainer.innerHTML = '<p>ID de solicitud no válido.</p>';
    }
});


//envio de datos al insert observaciones
document.getElementById('btn-EnviarObservacion').addEventListener('click', function () {
    var observaciones = document.getElementById('example-textarea-input').value;
    var file = filesToUpload.length ? filesToUpload[0] : null; // Tomar el primer archivo del array

    // Verificar que el campo de observaciones no esté vacío
    if (observaciones.trim() === "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor ingrese las observaciones.'
        });
        return;
    }

    if (!file) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se ha seleccionado ningún archivo.'
        });
        return;
    }

    // Crear un nuevo objeto FormData
    var formData = new FormData();
    formData.append('observaciones', observaciones);
    formData.append('idSolicitud', idSolicitud); // Usar el ID de la URL
    formData.append('file', file); // Aquí agregamos el archivo
    formData.append('fileName', file.name); // Agregar el nombre del archivo

    // Realizar la solicitud AJAX para subir el archivo
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "uploadObservaciones.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Guardar la ruta del archivo en la base de datos
                    var insertData = new FormData();
                    insertData.append('observaciones', observaciones);
                    insertData.append('idSolicitud', idSolicitud);
                    insertData.append('fileName', response.fileName);
                    insertData.append('filePath', response.filePath);

                    var insertXhr = new XMLHttpRequest();
                    insertXhr.open("POST", "insert_observacionSub.php", true); // Cambiar a insert_observacionSub.php

                    insertXhr.onreadystatechange = function () {
                        if (insertXhr.readyState === 4) {
                            if (insertXhr.status === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Observaciones y archivo enviados correctamente.'
                                }).then(() => {
                                    document.getElementById('example-textarea-input').value = '';
                                    myDropzone.removeAllFiles(true);
                                    // Redirigir después de eliminar todos los archivos
                                    window.location.href = 'RevisionDocument.php';
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error al insertar observaciones y archivo.'
                                });
                            }
                        }
                    };

                    insertXhr.send(insertData);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al subir el archivo.'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al subir el archivo.'
                });
            }
        }
    };

    xhr.send(formData);
});


document.getElementById('example-textarea-input').addEventListener('input', function (event) {
    // Reemplazar caracteres '<' y '>'
    this.value = this.value.replace(/[<>]/g, '');
});

//Guardar temporalmente los datos en el navegador
function saveFormData() {
    const formData = {
        nombre_carrera: document.getElementById('nombre-carrera').value,
        modalidad: document.getElementById('modalidad').value,
        universidad: document.getElementById('universidad').value,
        grado: document.getElementById('grado').value,
    };
    sessionStorage.setItem('formData', JSON.stringify(formData));
}

document.getElementById('formulario').addEventListener('submit', saveFormData);
