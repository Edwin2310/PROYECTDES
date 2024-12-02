// SubsanarDocumentos.js

Dropzone.autoDiscover = false;

var idSolicitud = new URLSearchParams(window.location.search).get('id'); // Obtener ID de la URL
var filesToUpload = []; // Array para almacenar los archivos

var myDropzone = new Dropzone("#dropzone-documents", {
    url: "uploadSub.php",
    dictDefaultMessage: "Arrastra y suelta un documento aquí",
    dictRemoveFile: "Eliminar archivo",
    acceptedFiles: ".pdf,.doc,.docx,.xls",
    maxFiles: 4,
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
                text: 'Solo se pueden subir hasta 4 archivos. Por favor, elimina un archivo antes de subir uno nuevo.',
            });
            this.removeFile(file);
        });

        this.on("addedfile", function (file) {
            // Validar nomenclatura del archivo antes de cualquier otra verificación
            var fileName = file.name;
            var now = new Date();
            var dateStr =
                now.getFullYear() +
                (now.getMonth() + 1).toString().padStart(2, "0") +
                now.getDate().toString().padStart(2, "0");

            var validPrefixes = ['SUBSOLI', 'SUBPLAN', 'SUBPDOC', 'SUBDIAG'];
            var isValid = false;

            // Verificar si el nombre del archivo cumple con la nomenclatura esperada
            for (var i = 0; i < validPrefixes.length; i++) {
                var expectedFormat = `${validPrefixes[i]}_${dateStr}`;
                var regex = new RegExp(`^${expectedFormat}(\\d{3})?\\.`);
                if (regex.test(fileName)) {
                    isValid = true;
                    break;
                }
            }

            if (!isValid) {
                self.removeFile(file);
                Swal.fire({
                    icon: 'error',
                    title: 'Nombre de archivo no válido',
                    text: `El nombre del archivo debe comenzar con alguno de los siguientes prefijos, seguido de la fecha en formato YYYYMMDD y un número secuencial de tres dígitos:
                    \nEjemplos permitidos: SUBSOLI_${dateStr}001.docx, SUBPLAN_${dateStr}002.pdf, SUBPDOC_${dateStr}003.docx, SUBDIAG_${dateStr}004.pdf`,
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Verificar si ya se subió un archivo con el mismo nombre
            if (filesToUpload.some(f => f.name === file.name)) {
                self.removeFile(file);
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo ya cargado',
                    text: 'Ya has subido un archivo con el mismo nombre.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            filesToUpload.push(file); // Guardar archivo en el array si pasa todas las validaciones
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
    // Obtener el ID de solicitud de los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const solicitudId = urlParams.get('id'); // Obtiene el valor del parámetro 'id' de la URL

    if (solicitudId) {
        fetch(`list_obs.php?id=${solicitudId}`)
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

document.getElementById('backButton').addEventListener('click', function() {
    window.history.back();
});

// Envío de datos a la subsanacionobs
document.getElementById('btn-EnviarArchivos').addEventListener('click', function () {
    var formData = new FormData();
    formData.append('idSolicitud', idSolicitud);

    // Agregar todos los archivos a formData
    filesToUpload.forEach(function(file, index) {
        formData.append('file[]', file); // Nota: 'file[]' para múltiples archivos
        formData.append('fileName[]', file.name); // Enviar los nombres de los archivos
    });

    // Realizar la solicitud AJAX para subir los archivos
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "uploadSub.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Manejar la respuesta y hacer la inserción de cada archivo en la base de datos
                    var fileDataList = response.uploadedFiles;
                    var fileUploadPromises = fileDataList.map(function(fileData) {
                        return new Promise(function(resolve, reject) {
                            var insertData = new FormData();
                            insertData.append('idSolicitud', idSolicitud);
                            insertData.append('fileName', fileData.fileName);
                            insertData.append('filePath', fileData.filePath);
                            insertData.append('fileType', fileData.fileType); // Añadir tipo de archivo

                            var insertXhr = new XMLHttpRequest();
                            insertXhr.open("POST", "subsanarDocs.php", true);

                            insertXhr.onreadystatechange = function () {
                                if (insertXhr.readyState === 4) {
                                    if (insertXhr.status === 200) {
                                        resolve();
                                    } else {
                                        reject('Error al insertar observaciones y archivo.');
                                    }
                                }
                            };

                            insertXhr.send(insertData);
                        });
                    });

                    Promise.all(fileUploadPromises)
                        .then(function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Observaciones y archivos enviados correctamente.'
                            }).then(() => {
                                myDropzone.removeAllFiles(true);
                                // Redirigir después de eliminar todos los archivos
                                window.location.href = 'index.php';
                            });
                        })
                        .catch(function(error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error
                            });
                        });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al subir los archivos.'
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al subir los archivos.'
                });
            }
        }
    };

    xhr.send(formData);
});

