Dropzone.autoDiscover = false;

var idSolicitud = new URLSearchParams(window.location.search).get('id'); // Obtener ID de la URL
var filesToUpload = []; // Array para almacenar los archivos

var myDropzone = new Dropzone("#dropzone-documents", {
    url: "UploadOR.php",
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
            formData.append("idSolicitud", idSolicitud);
            formData.append("tipoArchivo", "OpinionRazonada"); // Tipo de archivo
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

                var expectedFormat = `OR_${dateStr}`;
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


//Envia el archivo
document.getElementById('btn-enviar-archivo').addEventListener('click', function () {
    var acceptedFiles = myDropzone.getAcceptedFiles();
    var file = acceptedFiles.length ? acceptedFiles[0] : null;

    // Crear un nuevo objeto FormData para la primera solicitud
    var formData = new FormData();
    formData.append('idSolicitud', idSolicitud); // Usar el ID de la URL
    formData.append('file', file); // Aquí agregamos el archivo
    formData.append('fileName', file.name); // Agregar el nombre del archivo

    // Realizar la solicitud AJAX para subir el archivo
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "UploadOR.php", true);

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Guardar la ruta del archivo en la base de datos
                    var insertData = new FormData();
                    insertData.append('idSolicitud', idSolicitud);
                    insertData.append('fileName', response.fileName);
                    insertData.append('filePath', response.filePath);

                    var insertXhr = new XMLHttpRequest();
                    insertXhr.open("POST", "insert_archivofinal.php", true);

                    insertXhr.onreadystatechange = function () {
                        if (insertXhr.readyState === 4) {
                            if (insertXhr.status === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: 'Observaciones y archivo enviados correctamente.'
                                }).then(() => {
                                    // Limpiar Dropzone
                                    myDropzone.removeAllFiles(true); // Limpiar Dropzone
                                    window.location.href = 'index.php';
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

    xhr.send(formData); // Enviar el FormData con el archivo y otros datos
});


//Restaura los datos
function restoreFormData() {
    const formData = JSON.parse(sessionStorage.getItem('formData'));
    if (formData) {
        document.getElementById('nombre-carrera').value = formData.nombre_carrera;
        document.getElementById('modalidad').value = formData.modalidad;
        document.getElementById('universidad').value = formData.universidad;
        document.getElementById('grado').value = formData.grado;
    }

    document.getElementById('backButton').addEventListener('click', function () {
        window.history.back();
    });
}

window.onload = restoreFormData;