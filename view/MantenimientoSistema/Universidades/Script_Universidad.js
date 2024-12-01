function validarNombreUniversidad(input) {
    // Permite letras (con o sin tildes), espacios y elimina cualquier otro carácter
    input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóú\s]/g, '');

    // Convierte el texto a mayúsculas automáticamente
    input.value = input.value.toUpperCase();
}

// Función para mostrar mensajes de alerta
function showAlert(message, type) {
    Swal.fire({
        icon: type,
        title: type === 'success' ? 'Éxito' : 'Error',
        text: message,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Aceptar'
    });
}

// Mostrar alerta en caso de éxito al agregar universidad
function handleAddUniversitySuccess() {
    showAlert('Universidad añadida exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar universidad
function handleEditUniversitySuccess() {
    showAlert('Universidad editada exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar universidad (al cambiar IdVisibilidad)
function handleDeleteUniversitySuccess() {
    showAlert('La universidad ha sido bloqueado exitosamente. Podrás encontrarla en la pestaña de Universidades Bloqueadas.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateUniversity() {
    showAlert('Ya existe una universidad con los mismos datos.', 'error');
}

// Mostrar alerta en caso de error
function handleError(message) {
    showAlert(message, 'error');
}

// Ejecutar funciones basadas en parámetros URL
function handleURLParams() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('action')) {
        switch (urlParams.get('action')) {
            case 'add-success':
                handleAddUniversitySuccess();
                break;
            case 'edit-success':
                handleEditUniversitySuccess();
                break;
            case 'delete-success':
                handleDeleteUniversitySuccess();
                break;
            case 'duplicate':
                handleDuplicateUniversity();
                break;
            case 'edit-error':
            case 'delete-error':
                handleError(urlParams.get('message') || 'Error desconocido');
                break;
            default:
                break;
        }
    }
}

// Ejecutar al cargar el documento
document.addEventListener('DOMContentLoaded', function() {
    handleURLParams();
});