// Pasar datos al modal de edición
$("#editConsejalModal").on("show.bs.modal", function (event) {
  var button = $(event.relatedTarget);
  var id = button.data("id");
  var nombre = button.data("nombre");
  var apellido = button.data("apellido");
  var universidad = button.data("universidad");
  var categoria_cons = button.data("categoria_cons");
  var estado = button.data("estado");
  var correo_cons = button.data("correo_cons");

  var modal = $(this);
  modal.find("#edit-id").val(id);
  modal.find("#edit-nombre").val(nombre);
  modal.find("#edit-apellido").val(apellido);
  modal.find("#edit-universidad").val(universidad);
  modal.find("#edit-categoria").val(categoria_cons);
  modal.find("#edit-estado").val(estado);
  modal.find("#edit-correo").val(correo_cons);
});

// Pasar datos al modal de confirmación de eliminación
$("#confirmDeleteModal").on("show.bs.modal", function (event) {
  var button = $(event.relatedTarget); // Botón que abrió el modal
  var id_consejal = button.data("id");

  var modal = $(this);
  modal.find("#delete_id_consejal").val(id_consejal);
});

document.addEventListener("DOMContentLoaded", function () {
  // Obtener referencias a los campos del formulario
  const camposTexto = document.querySelectorAll('input[type="text"]');
  const camposEmail = document.querySelectorAll('input[type="email"]');

  // Función para validar campos de texto
  function validarTexto(campo) {
    campo.addEventListener("input", function () {
      this.value = this.value.replace(/\s/g, ""); // Elimina espacios en blanco
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
