// Permite solo números y no permite espacios
function validarNumeros(input) {
    input.value = input.value.replace(/[^0-9]/g, '');  // Solo números
}

function validarCategoria(input) {
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

// Mostrar alerta en caso de éxito al agregar categoria
function handleAddCategorySuccess() {
    showAlert('categoria añadida exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar categoria
function handleEditCategorySuccess() {
    showAlert('categoria editada exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar categoria (al cambiar IdVisibilidad)
function handleDeleteCategorySuccess() {
    showAlert('La categoria ha sido bloqueada exitosamente. Podrás encontrarla en la pestaña de categorias Bloqueadas.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateCategory() {
    showAlert('Ya existe una categoria con los mismos datos.', 'error');
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
                handleDeleteCategorySuccess();  // Se muestra el mensaje de éxito de eliminación
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
