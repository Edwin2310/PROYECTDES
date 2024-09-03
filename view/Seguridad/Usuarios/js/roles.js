$(document).ready(function () {
  // Mostrar datos en el modal de editar rol
  $("#editRoleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id_rol = button.data("id");
    var rol = button.data("rol");
    var descripcion = button.data("descripcion");
    var modal = $(this);
    modal.find("#edit-id_rol").val(id_rol);
    modal.find("#edit-rol").val(rol);
    modal.find("#edit-descripcion").val(descripcion);
  });

  // Validar formulario de agregar rol
  $("#addRoleForm").validate({
    rules: {
      rol: {
        required: true,
        minlength: 3,
        maxlength: 50,
      },
    },
    messages: {
      rol: {
        required: "Por favor ingrese el nombre del rol",
        minlength: "El nombre del rol debe tener al menos 3 caracteres",
        maxlength: "El nombre del rol no debe exceder los 50 caracteres",
      },
    },
  });

  // Validar formulario de editar rol
  $("#editRoleForm").validate({
    rules: {
      rol: {
        required: true,
        minlength: 3,
        maxlength: 50,
      },
    },
    messages: {
      rol: {
        required: "Por favor ingrese el nombre del rol",
        minlength: "El nombre del rol debe tener al menos 3 caracteres",
        maxlength: "El nombre del rol no debe exceder los 50 caracteres",
      },
    },
  });
});

$(document).ready(function () {
  // Capturar el ID del rol a eliminar y asignarlo al formulario de eliminación
  $("#confirmDeleteModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id_rol = button.data("id");
    var modal = $(this);
    modal.find("#delete-rol-id").val(id_rol);
  });

  // Actualizar 'Modificado Por' al abrir el modal de editar
  $("#editRoleModal").on("show.bs.modal", function (event) {
    var button = $(event.relatedTarget);
    var id_rol = button.data("id");
    // Asegúrate de que la función actualizarModificadoPor está definida y es accesible
    if (typeof actualizarModificadoPor === "function") {
      actualizarModificadoPor(id_rol);
    } else {
      console.error("La función actualizarModificadoPor no está definida.");
    }
    // Resto del código para mostrar datos en el modal de editar
    // ...
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencias a los campos del formulario
  const camposTexto = document.querySelectorAll('input[type="text"]');
  const camposEmail = document.querySelectorAll('input[type="email"]');

  // Función para validar campos de texto
  function validarTexto(campo) {
    campo.addEventListener("input", function () {
      this.value = this.value.replace(/[<>]/g, ""); // No permite < ni >
    });
  }

  // Aplicar la validación a cada campo de texto
  camposTexto.forEach(validarTexto);

  // Validación para campos de email
  camposEmail.forEach(function (campo) {
    campo.addEventListener("input", function () {
      this.value = this.value.replace(/[<>]/g, ""); // No permite < ni >
    });
  });

  // Bloquear copiar y pegar en todos los campos de texto
  camposTexto.forEach(function (campo) {
    campo.addEventListener("paste", function (event) {
      event.preventDefault();
    });
  });

  // Bloquear copiar y pegar en todos los campos de email
  camposEmail.forEach(function (campo) {
    campo.addEventListener("paste", function (event) {
      event.preventDefault();
    });
  });
});
