function validarCarrera(input) {
    // Remueve caracteres no permitidos (excepto letras con tildes y espacios)
    input.value = input.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóú\s]/g, '');

    // Reemplaza múltiples espacios consecutivos por un solo espacio
    input.value = input.value.replace(/\s+/g, ' ');

    // Elimina los espacios al principio o al final del texto
    input.value = input.value.trim();

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

// Mostrar alerta en caso de éxito al agregar carrera
function handleAddCareerSuccess() {
    showAlert('Carrera añadida exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar carrera
function handleEditCareerSuccess() {
    showAlert('Carrera editada exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar carrera
function handleDeleteCareerSuccess() {
    showAlert('La carrera ha sido bloqueada exitosamente. Podrás encontrarla en la pestaña de Carreras Bloqueadas.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateCareer() {
    showAlert('Ya existe una carrera con los mismos datos.', 'error');
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
                handleAddCareerSuccess();
                break;
            case 'edit-success':
                handleEditCareerSuccess();
                break;
            case 'delete-success':
                handleDeleteCareerSuccess();
                break;
            case 'duplicate':
                handleDuplicateCareer();
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
