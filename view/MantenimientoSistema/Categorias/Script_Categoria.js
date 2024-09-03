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

// Mostrar alerta en caso de éxito al agregar categoría
function handleAddCategorySuccess() {
    showAlert('Categoría añadida exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar categoría
function handleEditCategorySuccess() {
    showAlert('Categoría editada exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar categoría
function handleDeleteCategorySuccess() {
    showAlert('Categoría eliminada exitosamente.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateCategory() {
    showAlert('Ya existe una categoría con los mismos datos.', 'error');
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
                handleAddCategorySuccess();
                break;
            case 'edit-success':
                handleEditCategorySuccess();
                break;
            case 'delete-success':
                handleDeleteCategorySuccess();
                break;
            case 'duplicate':
                handleDuplicateCategory();
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
