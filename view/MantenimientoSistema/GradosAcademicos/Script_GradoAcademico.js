function validarGradoAcademico(input) {
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

// Mostrar alerta en caso de éxito al agregar Grado Académico
function handleAddGradeSuccess() {
    showAlert('Grado Académico añadido exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al editar Grado Académico
function handleEditGradeSuccess() {
    showAlert('Grado Académico editado exitosamente.', 'success');
}

// Mostrar alerta en caso de éxito al eliminar modalidad (al cambiar IdVisibilidad)
function handleDeleteGradeSuccess() {
    showAlert('El grado ha sido bloqueado exitosamente. Podrás encontrarlo en la pestaña de Grados Bloqueados.', 'success');
}

// Mostrar alerta si los datos ya existen
function handleDuplicateGrade() {
    showAlert('Ya existe una Grado Académico con los mismos datos.', 'error');
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
                handleAddGradeSuccess();
                break;
            case 'edit-success':
                handleEditGradeSuccess();
                break;
            case 'delete-success':
                handleDeleteGradeSuccess();
                break;
            case 'duplicate':
                handleDuplicateGrade();
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
