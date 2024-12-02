function vvalidarNombreDepartamento(input) {
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

// Mostrar alerta en caso de éxito al agregar departamento
function handleAddDepartmentSuccess() {
    showAlert('Departamento añadido exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar departamento
function handleEditDepartmentSuccess() {
    showAlert('Departamento editado exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar departamento
function handleDeleteDepartmentSuccess() {
    showAlert('El departamento ha sido bloqueada exitosamente. Podrás encontrarla en la pestaña de Departamentos Bloqueados.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateDepartment() {
    showAlert('Ya existe un departamento con los mismos datos.', 'error');
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
                handleAddDepartmentSuccess();
                break;
            case 'edit-success':
                handleEditDepartmentSuccess();
                break;
            case 'delete-success':
                handleDeleteDepartmentSuccess();
                break;
            case 'duplicate':
                handleDuplicateDepartment();
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
